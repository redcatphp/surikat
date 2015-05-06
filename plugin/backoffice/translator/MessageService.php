<?php namespace Translator;
use InterNative\msgfmt;
use InterNative\getTextExtractorTML;
use InterNative\getTextExtractorPHP;
use InterNative\Gettext\Extractors\Po;
use Database\R;
use Database\Query;
class MessageService {
	var $potfile = 'langs/messages.pot';
	private $db;
	function __construct() {
		$this->db = R::getStatic('translation');
		if(!$this->db->exists()){
			foreach(explode(';',file_get_contents(__DIR__.'/install.sql')) as $l)
				$this->db->execMulti($l);
		}
	}
	function cat($lang,$name){
		return new Catalogue($this->db,$lang,$name);
	}
	function getCountMessages($lang,$name){
		return (new Query('message',$this->db))
			->select('COUNT(*)')
			->where('catalogue_id=?',[$this->cat($lang,$name)->id()])
			->getCell()
		;
	}
	function getMessages($lang, $name, $page, $order, $sort) {
		$limit = 15;
		$offset = ($page-1)*$limit;
		switch($order){
			case 'fuzzy':
				$order = 'flags';
			break;
			case 'depr':
				$order = 'isObsolete';
			break;
			default:
				$order = 'msgid';
			break;
			case 'msgid':
			case 'msgstr':
			case 'isObsolete':
			case 'flags':
			break;
		}
		$messages = (new Query('message',$this->db))
			->where('catalogue_id=?',[$this->cat($lang,$name)->id()])
			->orderBy($order.' COLLATE NOCASE')
			->sort($sort)
			->limit($limit)
			->offset($offset)
			->getAll()
		;
		foreach($messages as &$m) {
			$m['fuzzy'] = strpos($m['flags'],'fuzzy') !== FALSE;
			$m['isObsolete'] = !!$m['isObsolete'];
		}
		return $messages;
	}
	function getCatalogues($lang){
		if(!$lang)
			return;
		$this->cat($lang,'messages');
		$names = [];
		foreach(glob('langs/*.pot') as $name)
			$names[] = pathinfo($name,PATHINFO_FILENAME);
		return $names;
	}
	function getStats($lang,$name){
		//return $this->db->getRow("SELECT c.name,c.id,COUNT(*) as message_count, COALESCE(SUM(LENGTH(m.msgstr) >0),0) as translated_count FROM catalogue c LEFT JOIN message m ON m.catalogue_id=c.id WHERE c.id=? GROUP BY c.id",[$this->cat($lang,$name)->id()]);
		return $this->db->getRow("SELECT c.name,c.id,COUNT(*) as message_count, COALESCE(SUM(LENGTH(m.msgstr) >0),0) as translated_count FROM catalogue c LEFT JOIN message m ON m.catalogue_id=c.id WHERE c.lang=? AND c.name=? GROUP BY c.id",[$lang,$name]);
	}
	function updateMessage($id, $comments, $msgstr, $fuzzy){
		$flags = $fuzzy&&$fuzzy!='false' ? 'fuzzy' : '';
		$this->db->exec("UPDATE message SET comments=?, msgstr=?, flags=? WHERE id=?", [$comments, $msgstr, $flags, $id]);
	}
	function importCatalogue($lang,$name,$atline=null){
		if($lang&&$name)
			return $this->cat($lang,$name)->import($this->potfile,$atline);
	}
	function exportCatalogue($lang,$name){
		if(!$lang||!$name)
			return;
		$path = 'langs/'.$lang.'/LC_MESSAGES/'.$name.'.';
		@mkdir(dirname($path),0777,true);
		$po = $path.'po';
		$mo = $path.'mo';
		$this->cat($lang,$name)->export($po);
		msgfmt::convert($po,$mo);
		foreach(glob($path.'*.mo') as $f)
			unlink($f);
		copy($mo,$path.time().'.mo');
	}
	function cleanObsolete($lang,$name){
		$this->db->exec("DELETE FROM message WHERE catalogue_id=? AND isObsolete=1",[$this->cat($lang,$name)->id()]);
	}
	
	function makePot(){
		$potfile = $this->potfile;
		$pot = Catalogue::headerPots();
		$pot = str_replace("{ctime}",gmdate('Y-m-d H:iO',is_file($potfile)?filemtime($potfile):time()),$pot);
		$pot = str_replace("{mtime}",gmdate('Y-m-d H:iO'),$pot);
		if(defined('SURIKAT_CWD'))
			$cwd = SURIKAT_CWD;
		else
			$cwd = getcwd();
		$pot .= getTextExtractorTML::parse('tml',$cwd);
		$pot .= getTextExtractorPHP::parse('tml',$cwd);
		$pot .= getTextExtractorPHP::parse('php',$cwd);
		file_put_contents($potfile,$pot);
	}
	function countPotMessages(){
		//return (new POParser())->countEntriesFromStream(fopen('langs/messages.pot', 'r'));
		return count(Po::fromFile('langs/messages.pot')->getArrayCopy());
	}
}