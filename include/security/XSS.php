<?
function getXss( &$arr, $name)
{
	$return = null;
	$var = $arr[$name];
	if (isset( $var ))
	{
		if (is_string( $var ))
		{
			$var = trim( $var );
			$var = strip_tags( $var );
			$var = str_replace("'","&lsquo;", $var);
			$var = str_replace('"',"&quot;", $var);
		}
		return $var;
	}else 
	{
		return NULL;
	}
}

function getXssE( &$arr, $name)
{
	require_once("HTMLCleaner.php");
	//$return = NULL;
	$var = $arr[$name];
	if (isset( $var ))
	{
		if (is_string( $var ))
		{
			$cleaner=new HTMLCleaner(); 
			$cleaner->html=$var;
			//$var = $cleaner->cleanUp('latin1');
			
			$var = trim( $var );
			$var = str_replace('\r\n'," ", $var);
			$var = str_replace("'","&lsquo;", $var);
		}
		return $arr[$name];
	}else
	{
		return NULL;
	}
}
?>
