<?php

class admin_mimes{
	static $private='6';
	static $langs=array('en','fr');

	static function headers(){
		Head::add('csscode','fieldset, legend{border:0; background:#ddd; width:44%; display:table-cell;}');
	}
	
	//install
	static function install(){
		Sql::create('mimes',array('ref'=>'var','icon'=>'var'));
	}
	
	//save
	static function update($prm){$rid=$prm['rid'];
		Sql::update('mimes','icon',$prm[$rid],$prm['id']);
		return self::com($prm);
	}
	
	static function del($prm){
		$nid=Sql::delete('mimes',$prm['id']);
		return self::com($prm);
	}
	
	static function save($prm){//$lang=val($prm,'lang');,$lang
		$nid=Sql::insert('mimes',array($prm['ref'],$prm['icon']));
		return self::com($prm);
	}
	
	static function edit($prm){$rid=randid('mimes');//id
		$r=Sql::read('ref,icon','mimes','ra','where id='.$prm['id']);
		$ret=label($rid,$r['ref']);
		$ret.=goodinput($rid,$r['icon']);
		$ret.=Ajax::j('admm,,x|admin_mimes,update|id='.$prm['id'].',rid='.$rid.'|'.$rid,lang('save'),'btsav');
		$ret.=Ajax::j('admm,,x|admin_mimes,del|id='.$prm['id'],lang('del'),'btdel');
		$ret.=ajax('popup','icons','','',icon('eye'),'btn');
		return $ret;
	}
	
	static function add($prm){//ref,icon
		$ref=val($prm,'ref'); $icon=val($prm,'icon');
		$ret=input('ref',$ref?$ref:'ref',16,1).input('icon',$icon?$icon:'icon',16,1);
		$ret.=Ajax::j('admm|admin_mimes,save||ref,icon',lang('save'),'btn');
		return $ret;
	}
	
	//table
	static function select(){$ret='';
		if(ses('auth')>6){
			$ret.=Ajax::j('popup|admin_mimes,add',icolang('add'),'btn');
			$ret.=Ajax::j('popup|Sql,mkbcp|b=mimes',icolang('backup'),'btn');
			if(Sql::exists('mimes_bak'))
			$ret.=Ajax::j('popup|Sql,rsbcp|b=lang',icolang('restore'),'btdel');}
			$ret.=Ajax::j('admm|admin_mimes',icolang('reload'),'btn').br();
		return $ret;
	}
	
	static function com(){$rb=array();
		$bt=self::select().br();
		$r=Sql::read('id,ref,icon','mimes','','order by ref');
		if($r)foreach($r as $k=>$v){
				$ref=Ajax::j('popup|admin_mimes,edit|id='.$v[0],$v[1],'btn');
				$icon=icon($v[2]);
			if(!$v[2])$rc[$k]=array($ref,$icon); else $rb[$k]=array($ref,$icon);}
		if(isset($rc))$rb=array_merge($rc,$rb);
		array_unshift($rb,array('ref','icon'));
		return $bt.Build::table($rb,'bkg');
	}
	
	//content
	static function content($prm){$ret='';
		self::install();
		$ret=self::com();
		return div($ret,'','admm');
	}

}

?>