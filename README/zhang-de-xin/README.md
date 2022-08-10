## 【创新实践能力团队赛】总结技术报告——zhang-de-xin



### 一、负责的工作

我和另一位同学xt0103负责搭建服务器和数据库，使前端同学制作的网站可以在我们的服务器上运行并可以访问数据库调用存储数据。



### 二、实验过程和记录

#### 1.实验环境

Apache 2.4.54

PHP 8.1.8

Windows 10

MySQL 8.0.29.0



#### 2.Apache服务器安装

我们选用的是apache服务器，官网下载对应版本的apache，解压文件，修改`conf`文件夹里的配置文件`httpd.conf`的路径如下：

> 该路径是apache的安装路径。

![1](img\1.png)

管理员权限打开cmd，进入到`bin`文件夹，安装并启动服务，如下所示：

```bash
httpd -k install
httpd -k start
```

![2](img\2.png)

浏览器访问 http://localhost/ 测试是否安装成功，显示如下则表示apache安装成功：

![3](img\3.png)



#### 3.PHP安装

官网下载PHP，解压文件，将其放到自己喜欢的位置（这里我放在了`Apache`文件夹下），然后修改apache的`httpd.conf`配置文件，在如下位置进行PHP配置：

> 路径为解压的PHP文件夹里对应的文件路径

![4](img\4.png)

将PHP安装目录下的 `php.ini-development` 文件复制一份，将复制的那一份文件名改成`php.ini`，修改`php.ini`如下：

> 因为代码行数很多，可以使用查找功能查找关键词来找到对应的部分

![7](img\7.png)

![6](img\6.png)

编写一个php测试文件，`test.php`内容如下：

```php
<?php
    phpinfo();
?>
```

将其放在Apache的htdocs文件夹下，这里应该可以看到一个index.html文件（测试Apache是否安装成功的那个页面)），然后访问 http://localhost/test.php 出现下面页面表示成功：

![5](img\5.png)



#### 4.MySQL安装和配置

官网下载MySQL，通过下载的文件`mysql-installer-community-8.0.29.0.msi`进行数据库安装：

<img src="img\18.png" alt="18" style="zoom:80%;" />

我们的数据库只使用了一个表，用于存储用户信息，即账号和密码：

> 我们将该数据库导出了，即`data.sql`文件

![21](img\21.png)

我们还需要在mysql里手动添加一个user，赋予所有权限，密码：*8272D64B4DB9D7DF622DF834A91F9BEA74C6CE6C

![19](img\19.png)

![20](img\20.png)



#### 5.运行网页代码

> 这部分是我和另一位后端同学觉得最麻烦的部分，因为一步步排了很多报错才让网页实现了绝大部分功能，我们都是一起上网查找资料寻找解决办法的。

我们搭建好服务器、数据库和环境后，需要将网页代码运行起来，使其能在服务器上正常访问和跳转。将网站文件复制到apache的`htdocs`文件夹下：

> src 存的是网站的相关文件
>
> index.html 是apache的默认页面，即我们测试apache是否安装成功的那个页面
>
> test.php 和 test2.php 都是我们写的测试文件，用来查找报错问题的

![22](img\22.png)

> 以下为 src 文件夹的内容，即网站文件

![23](img\23.png)

尝试访问登录页面 http://localhost/src/login.php ，结果有如下报错：

![8](img\8.png)

根据报错提示的信息，找到相关文件`AuthLocal.class.php`的代码，查阅资料后得知这是数据库连接函数，需要使用mysqli，于是修改函数如下：

![9](img\9.png)

但依旧没有解决问题，后来参考 [这篇文章](https://blog.csdn.net/www121104115/article/details/75006164?ops_request_misc=%257B%2522request%255Fid%2522%253A%2522165770656016781435425388%2522%252C%2522scm%2522%253A%252220140713.130102334..%2522%257D&request_id=165770656016781435425388&biz_id=0&utm_medium=distribute.pc_search_result.none-task-blog-2~all~baidu_landing_v2~default-3-75006164-null-null.142^v32^pc_rank_34,185^v2^control&utm_term=%20Call%20to%20undefined%20function%20mysqli_connect%28%29%20in%20&spm=1018.2226.3001.4187) 后终于解决了。

1. 需要修改php.ini：

   ```
   ;extension=php_mysqli.dll
   改为
   extension=php_mysqli.dll
   ```
2. 增添环境变量：

   ![10](img\10.png)
3. 访问 http://localhost/test.php 如果可以找到mysqli的如下配置界面则表示配置成功

   ![11](img\11.png)

解决上面的问题后，编写 test2.php 测试是否可以连接上MySQL：

```php
<?php
$conn=mysqli_connect('localhost','用户名','密码','数据库');

if ($conn) {
	echo '连接成功';
}else{
	echo "连接失败";
}
?>
```

![12](img\12.png)

但是再测试我们的登录界面`login.php`文件时，又遇到如下错误：

> Fatal error: Uncaught mysqli_sql_exception: Access denied for user 'ftp'@'localhost' (using password: YES) in D:\Apache\httpd-2.4.54-o111p-x64-vs17\Apache24\htdocs\src\core\AuthLocal.class.php:48 Stack trace: #0 D:\Apache\httpd-2.4.54-o111p-x64-vs17\Apache24\htdocs\src\core\AuthLocal.class.php(48): mysqli_connect('localhost', 'ftp', 'ftpuser') #1 D:\Apache\httpd-2.4.54-o111p-x64-vs17\Apache24\htdocs\src\login.php(11): WF_Auth::loginCheck() #2 {main} thrown in D:\Apache\httpd-2.4.54-o111p-x64-vs17\Apache24\htdocs\src\core\AuthLocal.class.php on line 48
>

检查后发现是 `mysql.php` 内的密码不对，将密码修改成我们数据库里面存的密码，修改后又遇到新错误：

> Fatal error: Uncaught ArgumentCountError: Too few arguments to function error_handler_fun(), 4 passed and exactly 5 expected in D:\Apache\httpd-2.4.54-o111p-x64-vs17\Apache24\htdocs\src\core\Functions.php:193 Stack trace: #0 [internal function]: error_handler_fun(8, 'session_start()...', 'D:\\Apache\\httpd...', 56) #1 D:\Apache\httpd-2.4.54-o111p-x64-vs17\Apache24\htdocs\src\core\AuthLocal.class.php(56): session_start() #2 D:\Apache\httpd-2.4.54-o111p-x64-vs17\Apache24\htdocs\src\login.php(11): WF_Auth::loginCheck() #3 {main} thrown in D:\Apache\httpd-2.4.54-o111p-x64-vs17\Apache24\htdocs\src\core\Functions.php on line 193
>

发现是 `Functions.php` 内的函数多了一个无用参数导致的，将这个参数删除即可：

![13](img\13.png)

然后又遇到如下错误：

> **Fatal error**: Uncaught Error: Call to undefined function mysql_db_query() in D:\Apache\httpd-2.4.54-o111p-x64-vs17\Apache24\htdocs\src\core\AuthLocal.class.php:59 Stack trace: #0 D:\Apache\httpd-2.4.54-o111p-x64-vs17\Apache24\htdocs\src\login.php(11): WF_Auth::loginCheck() #1 {main} thrown in **D:\Apache\httpd-2.4.54-o111p-x64-vs17\Apache24\htdocs\src\core\AuthLocal.class.php** on line **59**

总结以后发现是mysql和mysqli的问题，将所有mysql函数改为mysqli函数就行，对`AuthLocal.class.php`做如下4处修改后即可：

修改前：

![14](img\14.png)

修改后：

![15](img\15.png)

![16](img\16.png)

终于成功加载了login界面：

![24](img\24.png)

但进入 `index.php` 后又遇到如下问题，说明我们的代码的文件路径是有问题的：

![17](img\17.png)

找到报错“文件系统错误，无法访问UESR目录”的相关代码如下，位于`webftp.php`文件内：

![25](img\25.png)

根据代码一步步向上回溯，编写`test2.php`进行测试，将需要的变量和函数完全复制过来，进行测试：

```php
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
```

得到以下测试结果：

![27](img\27.png)

说明root路径是正确的，并且可以使用的，而user路径没有输出。

经过排查，可能是这个函数的问题：

![26](img\26.png)

> realpath(wf_config('ROOT_PATH'))
> 报错realpath不能是null，而wf_config()函数返回的是null，好像是这样，所以不能输出user路径

简单说一下现在的问题，user目录的路径不对，所以我暂时改成root路径了，应该有一个user用的文件夹来存user上传的文件，但是我没有找到，可能要我们自己弄一个。

修改如下：

> 让user使用root的路径，原本的代码被我注释了，不过这样并没完全解决这个问题，不过网站可以使用了

![28](img\28.png)

成功进入文件管理界面，不过是root目录：

![29](img\29.png)

经过测试，网站的功能都能使用，剩下的部分就交给负责攻击网站的同学进行实验了。




### 参考资料

[MySQL, Apache, PHP 安装教程](https://blog.csdn.net/u012519228/article/details/51591562)

[超详细MySQL安装及基本使用教程](https://blog.csdn.net/theLostLamb/article/details/78797643)

