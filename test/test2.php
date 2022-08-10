<?php
    //数据库用户名：root  密码：root 数据库：abc
$conn=mysqli_connect('localhost','ftp','*8272D64B4DB9D7DF622DF834A91F9BEA74C6CE6C','ftp');
//简单测试一下
if ($conn) {
	echo '连接成功';
}else{
	echo "连接失败";
}


// 全局配置数组
$_CONFIG = array(
	'SYSTEM_NAME'           => 'WebFTP',
	'SYSTEM_VERSION'        => 'v3.6.2 专业版',

	/* 日志设置 */
	'LOG_ON'  				=> true, 	// 记录日志
	'LOG_TYPE'    			=> 'EMERG,ALERT,CRIT,ERR,WARNING,NOTICE,INFO,DEBUG',
	'LOG_FILE_SIZE'         => 2097152, // 默认2MB
	'LOG_SAVE_TYPE'         => 2,       // 1：只保留最新日志,2: 保留所有日志，

	/* 根目录设置 */
	'ROOT_PATH' => './data/nfs', // 系统存储根路径，请勿随意修改
	'USER_PATH' => '/_xx_',       // 用户存储虚拟路径，请勿随意修改

	/* 文件上传配置 */
	'UPLOAD' => array(
		'chunk_size'    => min(8, intval(ini_get('upload_max_filesize'))), // 文件分块大小，单位MB
		'max_file_size' => 1024,  // 上传单个文件限制大小，单位MB
		'filters' => array(
			array('All Files (*.rar;*.htm;*.jpg;*.pdf;*.doc;*.*)', '*,rar,zip,tar,gz,7z,php,js,css,htm,html,xml,jpg,png,gif,bmp,ico,pdf,doc,ppt,xls,docx,pptx,xlsx,wps,et,dps'),
			array('Archive Files (*.rar;*.zip;*.tar;*.gz;*.7z)', 'rar,zip,tar,gz,7z'),
			array('Script Files (*.php;*.js;*.css;*.htm;*.xml)', 'php,js,css,htm,html,xml'),
			array('Images Files (*.jpg;*.png;*.gif;*.bmp;*.ico)', 'jpg,png,gif,bmp,ico'),
			array('Document Files (*.doc;*.ppt;*.xls;*.pdf;wps;*.et;*.dps)', 'pdf,doc,ppt,xls,docx,pptx,xlsx,wps,et,dps'),
		)
	),

);

function wf_config($name=null, $value=null){
	static $_config = array();
	// 无参数时获取所有
	if (empty($name)){
		return $_config;
	}

	// 优先执行设置获取或赋值
	if (is_string($name)){
		$name = strtolower($name);
		if (false === strpos($name, '.')) {
			if (is_null($value)){
				return isset($_config[$name]) ? $_config[$name] : null;
			} else{
				return $_config[$name] = $value;
			}
		}

		// 二、三维数组设置和获取支持
		$name = explode('.', $name);
		if (false === isset($name[2])){
			if (is_null($value)){
				return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : null;
			} else{
				return $_config[$name[0]][$name[1]] = $value;
			}
		} else{
			if (is_null($value)){
				return isset($_config[$name[0]][$name[1]][$name[2]]) ? $_config[$name[0]][$name[1]][$name[2]] : null;
			} else{
				return $_config[$name[0]][$name[1]][$name[2]] = $value;
			}
		}
	}
	//批量设置
	if (is_array($name)){
		return $_config = array_merge($_config, array_change_key_case($name, CASE_LOWER));
	}
	//避免非法参数
	return null;
}
function wf_u2g($str)
{
	return iconv('UTF-8', 'GB2312//IGNORE', $str);
}

define('WF_REAL_ROOT_PATH', str_replace('\\', '/', realpath('ROOT_PATH')));
//define('WF_REAL_ROOT_PATH', str_replace('\\', '/', realpath(wf_config('ROOT_PATH'))));
define('WF_SYS_WIN', 'WIN' === strtoupper(substr(PHP_OS, 0,3)));
$_SESSION['wf_uroot'] = WF_REAL_ROOT_PATH;
$_SESSION['wf_upath'] = '/';


$REAL_ROOT_PATH = realpath( $_SESSION['wf_uroot'] );
$REAL_USER_PATH = realpath( $_SESSION['wf_uroot'] . '/' . (WF_SYS_WIN ? wf_u2g($_SESSION['wf_upath']) : $_SESSION['wf_upath']) );
print "\n";
print "ROOT_PATH is: $REAL_ROOT_PATH";
print "\n";
print "USER_PATH is: $REAL_USER_PATH";

$a = wf_config('ROOT_PATH');
$b = realpath(WF_REAL_ROOT_PATH);
print "\n";
print "test is: $b";

//执行操作
//$abc = mysqli_query( $con , 'insert into a(id) values(125)');
//var_dump($abc);
?>