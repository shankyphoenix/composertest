<?php
namespace core;
/**
 *Test case
 *
 *Hello world
 *@package Essay
 *@author shanky
 *@version -1.1
 *
 */

class sValidation
{
	var $serrorflag;
	var $serror;
	var $chkpostvariable;
	var $fieldvariablenames;
		
		
	function __construct($arr)
	{
		$this->serrorflag= false;
		$this->serror = array();
		$this->chkpostvariable = false;
		$this->fieldvariablenames = $arr;
		
		if($this->checkpostvariable())
		{
			if(is_array($this->fieldvariablenames) && count($this->fieldvariablenames) > 0)
			{
				
				$this->checkvalidation();
				$this->serrorflag;

			}
			else
			{
				
			}
		}
		
		
	}
	
	function file_upload_error_message($error_code) {
		switch ($error_code) {
			case UPLOAD_ERR_INI_SIZE:
				return ('The uploaded file exceeds the upload_max_filesize directive in php.ini');
			case UPLOAD_ERR_FORM_SIZE:
				return ('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form');
			case UPLOAD_ERR_PARTIAL:
				return ('The uploaded file was only partially uploaded');
			case UPLOAD_ERR_NO_FILE:
				return ('No file was uploaded');
			case UPLOAD_ERR_NO_TMP_DIR:
				return ('Missing a temporary folder');
			case UPLOAD_ERR_CANT_WRITE:
				return ('Failed to write file to disk');
			case UPLOAD_ERR_EXTENSION:
				return ('File upload stopped by extension');
			default:
				return ('Unknown upload error');
		}
	} 
	function vcheckfiles($data,$fieldname,$errortype)
	{
	
	
		//nofile-jpg,png,jpeg-500
	
		$arrerror = explode("-",$errortype);
		
		$filename = $_FILES[$fieldname]['name'];
		if ($_FILES[$fieldname]['error'] === UPLOAD_ERR_OK)
		{
		
			if($_FILES[$fieldname]["size"] > $arrerror[2])
			{
				$this->serrorflag = true;
				$this->serror[$fieldname][] = ('File size should be less than '.$arrerror[2].' KB');
				return;
			}
			 $ext = substr($filename, strpos($filename,'.')+1, strlen($filename)-1);
			$imageType = explode(',',$arrerror[1]);
			if(!in_array($ext,$imageType))
			{
				$this->serrorflag = true;
				$this->serror[$fieldname][] = ('Invalid format');
				return;
			}
		}
		else
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = $this->file_upload_error_message($_FILES[$fieldname]['error']);
		}
		
	}
	function checkpostvariable()
	{
		if(isset($_POST))
		{
			
			$this->chkpostvariable = true;
		}
		return $this->chkpostvariable;
	}
	function checkvalidation()
	{
		
		foreach($this->fieldvariablenames as $key => $val)
		{
			
			$this->checkfieldvalidation($val);
		}
	}
	function checkfieldvalidation($arrdata)
	{
	
		$fieldname = $arrdata[0];
		$fielddata = $_POST[$arrdata[0]];
		$arrtype = explode("|",$arrdata[1]);
		if(is_array($arrtype) && count($arrtype) > 0)
		{
			foreach($arrtype as $key => $val)
			{
				$this->checkFieldAgainstdata($fielddata,$fieldname,$val);
			}
		}
		
	}
	function checkFieldAgainstdata($fielddata,$fieldname,$errortype)
	{
		$arrerror = explode("-",$errortype);
		$functionname = "v".$arrerror[0];
		$this->$functionname($fielddata,$fieldname,$errortype);
	}
	function vcheckdate($fielddata,$fieldname,$errortype)
	{
		$date = $_POST['datejob']." ".$_POST['datetime'];
		if(strtotime($date) <= time())
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "You can't leave this empty.";
		}
	}
	function vrequired($data,$fieldname,$errortype)
	{
		if($data == "")
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = ("You can't leave this empty.");
		}
	}
	function vrequiredarraycount($data,$fieldname,$errortype)
	{
		if(count($_REQUEST[$fieldname])<=0)
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "Choose atleast one user";
		}
	}
	
	
	function valpha($data,$fieldname,$errortype)
	{
		if(!preg_match("/^[a-zA-Z ]+$/",$data))
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "Please use only letters (a-z)";
		}		
	}
	function valphanumberic($data,$fieldname,$errortype)
	{
		if(!preg_match("/^[a-zA-Z]+[\w ]+$/",$data))
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "Please use only letters (a-z), numbers, and periods";
		}		
	}
	
	function vnumberic($data,$fieldname,$errortype)
	{
		if(!preg_match("/^[0-9\.]+$/",$data))
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "Invalid format";
		}		
	}
	function vchar_exactly($data,$fieldname,$errortype)
	{
		$arrerror = explode("-",$errortype);
		
		if(strlen($data) != $arrerror[1])
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "vchar_exactly";
		}		
	}
	function vnum_exactly($data,$fieldname,$errortype)
	{
		$arrerror = explode("-",$errortype);
		
		if($data != $arrerror[1])
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "vnum_exactly";
		}		
	}
	function vchar_range($data,$fieldname,$errortype)
	{
		$arrerror = explode("-",$errortype);
		
		if(strlen($data) < $arrerror[1] || strlen($data) > $arrerror[2])
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "Please use between ".$arrerror[1]." and ".$arrerror[2]." characters.";
		}		
	}
	function vnum_range($data,$fieldname,$errortype)
	{
		$arrerror = explode("-",$errortype);
		
		if($data < $arrerror[1] || $data > $arrerror[2])
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "Please use between ".$arrerror[1]." and ".$arrerror[2]." numbers.";
		}		
	}			function vimage($data,$fieldname,$errortype)	{		$arrerror = explode("-",$errortype);		$field1 = $arrerror[1];		$field2 = $arrerror[2];				if($_POST[$field1] != $_POST[$field2])		{			$this->serrorflag = true;			$this->serror[$fieldname][] = "These field don't match. Try again?";		}			}			
	function vcomparefileds($data,$fieldname,$errortype)
	{
		
		$arrerror = explode("-",$errortype);
		$field1 = $arrerror[1];
		$field2 = $arrerror[2];
		//echo $_REQUEST[$field1];
		//echo $_REQUEST[$field2];
		if($_POST[$field1] != $_POST[$field2])
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "These field don't match. Try again?";
		}		
	}
	function vcheckdatetest($data,$fieldname,$errortype)
	{
		$arrerror = explode("-",$errortype);
		//(0?[1-9]|[12][0-9]|3[01])/(0?[1-9]|1[012])/((19|20)\\d\\d)
		if(strlen($data) == 8 || strlen($data) == 10)
		{
			if(strlen($data) == 8)
			{
				
			}
			else
			{
				
			}			
		}
		
		
		if($arrerror[1] == "yyyy-mm-dd" && ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $data))
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "char_range";
		}
	}
	
	
	function vcustomdateselector($date,$fieldname)
	{
		if(strtotime($date) < time())
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "Please select a future date.";
		}
		
	}
	
	function vemail($data,$fieldname,$errortype)
	{
		if(!ereg("^[-A-Za-z0-9_]+[-A-Za-z0-9_.]*[@]{1}[-A-Za-z0-9_]+[-A-Za-z0-9_.]*[.]{1}[A-Za-z]{2,5}$", $data))
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "Invalid email format";
		}		
	}
	
	function vcaptcha($data,$fieldname,$errortype)
	{
		$arrerror = explode("-",$errortype);
		$captchafield = $arrerror[1];
		$sessioncaptchavalue = (isset($_SESSION[$captchafield]))?$_SESSION[$captchafield]:"";
		
		if($data != $sessioncaptchavalue)
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = "The characters you entered didn't match the word. Try again. ";
		}		
	}
	function checkrowexistquery($fieldname,$query,$checkagintflag,$message="found")
	{
		global $essaylink;
		if($result = mysql_query($query,$essaylink) or die(mysql_error()))
		{
			$row = mysql_fetch_array($result);
			if($row['c'] >0)
			{
				$flag = true;
			}
			else
			{
				$flag = false;
			}
		}
		else
		{
			$flag = false;
		}
		if($checkagintflag != $flag)
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = $message;
		}
	}
	function checkrowexist($fieldname,$table,$array,$checkagintflag,$message="found")
	{
		global $essaylink;
		$flag = false;
		
		$arrwhere = array();
		if(is_array($array))
		{
			foreach($array as $key => $value)
			{
				$arrwhere[] = " $key = '".$value."' ";
			}
			$strwhere = implode(" and ",$arrwhere);
		}
		$query = "select count(*) as c from $table where $strwhere";
		//echo $query;
		if($result = mysql_query($query,$essaylink))
		{
			$row = mysql_fetch_array($result);
			if($row['c'] >0)
			{
				$flag = true;
			}
			else
			{
				$flag = false;
			}
		}
		else
		{
			$flag = false;
		}
		
		if($checkagintflag != $flag)
		{
			$this->serrorflag = true;
			$this->serror[$fieldname][] = $message;
		}		
	}
	
	
}


if(isset($_REQUEST['testitnow']))
{
$_POST['tt'] ="4";
$_POST['ddd'] ="a@a.comdfff";

/*
$arr = array
		(
			array('tt','required'),
			array('ddd','required')			
		);
	*/
		
	include('../connection/connection.php');
	$_POST['user'] ="ajay";
	$a = new svalidation($arr);
	$a->checkrowexist("user","users",array("username"=>$_POST['user'],
										   "id"=>$_POST['user']),true,"user not found");
	$a->checkrowexist("email","users",array("username"=>$_POST['user'],
										   "id"=>$_POST['user']),true,"user not found");
if($a->serrorflag)
{
	echo "<pre>";
	print_r($a->serror);
}
	
}
// $_POST['tt'] ="4";
// $_POST['ddd'] ="a@a.comdfff";

// $arr = array
		// (
			// array('tt','required'),
			// array('ddd','required|comparefileds-tt-ddd')			
		// );

// $a = new svalidation($arr);
// if($a->serrorflag)
// {
	// echo "<pre>";
	// print_r($a->serror[]);
//}
