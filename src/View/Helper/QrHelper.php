<?php
namespace App\View\Helper;

use Cake\ORM\TableRegistry; 
use Cake\View\Helper;
use Cake\Datasource\ConnectionManager;

class QrHelper extends Helper {
	var $helpers=array('Html');
	var $size='300x300';//QR Code size
	var $encode='UTF-8';
	/**
	* Error correction level
	* L - [Default] Allows recovery of up to 7% data loss
	* M - Allows recovery of up to 15% data loss
	* Q - Allows recovery of up to 25% data loss
	* H - Allows recovery of up to 30% data loss
	*/
	var $error_correction='H';
	var $margin=4;//margin around chart data portion
	//Google API QR Code Generation 
	var $base_url='http://chart.googleapis.com/chart?cht=qr&chl=';

	//Text encoder
	function text($text='',$options=array()){
		return $this->Html->image($this->base_url . urlencode($text).$this->_optionsString($options));
	}

	//URL encoder
	function url($url='',$options=array()){
		$url= Router::url($url,true);
		return $this->Html->image($this->base_url . urlencode($url).$this->_optionsString($options));
	}

	//Email encoder
	function email($mail='',$options=array()){
		return $this->Html->image($this->base_url . urlencode('mailto:'.$mail).$this->_optionsString($options));
	}
	//Phone encoder
	function telephone($phone='',$options=array()){
		return $this->Html->image($this->base_url . urlencode('tel:'.$phone).$this->_optionsString($options));
	}

	//Contact encoder
	function contact($contact=array(),$options=array()){
		$ret = "";
		//$ret='MECARD:';
		if(isset($contact['id'])){
			$ret.=$contact['id'].',';
		}
		if(isset($contact['text'])){
			$ret.='MEM_ID:'.$contact['text'].';';
		}
		if(isset($contact['name'])){
			$ret.='N:'.$contact['name'].';';
		}
		if(isset($contact['address'])){
			$ret.='ADR:'.$contact['address'].';';
		}
		if(isset($contact['phone'])){
			$ret.='TEL:'.$contact['phone'].';';
		}
		if(isset($contact['email'])){
			$ret.=$contact['email'];
		}
		if(isset($contact['url'])){
			$ret.='URL:'.$contact['url'].';';
		}
		if(isset($contact['note'])){
			$ret.='NOTE:'.$contact['note'].';';
		}
	$url=$this->base_url . urlencode($ret).$this->_optionsString($options);
	//return $this->Html->image($url);
	return $url;
	}

	//Send a SMS to a given number
	function sms($number='',$options=array()){
		return $this->Html->image($this->base_url . urlencode('sms:'.$number).$this->_optionsString($options));
	}

	//Send a MMS to a given number
	function mms($number='',$options=array()){
		return $this->Html->image($this->base_url . urlencode('mms:'.$number).$this->_optionsString($options));
	}
	
	//Geocode encoder
	function geo($geo=array(),$options=array()){
		if(!isset($geo['lat'])){
			$geo['lat']='';
		}
		if(!isset($geo['lon'])){
			$geo['lon']='';
		}
		if(!isset($geo['height'])){
			$geo['height']='2000';
		}
	return $this->Html->image($this->base_url . urlencode('geo:'.$geo['lat'].','.$geo['lon'].','.$geo['height']).$this->_optionsString($options));
	}
	
	//Android Play store search encoder
	function market($app='',$options=array()){
		return $this->Html->image($this->base_url . urlencode('market://search?q='.$app).$this->_optionsString($options));
	}

	//Merges all options array and returns as url parameter string
	function _optionsString($options){
		if(!isset($options['size'])){
			$options['size']=$this->size;
		}
		if(!isset($options['encode'])){
			$options['encode']=$this->encode;
		}
		if(!isset($options['error_correction'])){
			$options['error_correction']=$this->error_correction;
		}
		if(!isset($options['margin'])){
			$options['margin']=$this->margin;
		}
	return'&chs='.$options['size'].'&choe='.$options['encode'].'&chld='.$options['error_correction'].'|'.$options['margin'];
	}
}
?>