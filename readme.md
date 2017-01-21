[TOC]
# 开发Laravel扩展并发布到packagist.org
    试试 composer require baoniu/hasher
## 开发package
### 创建项目目录
    laravel new package-lesson
    cd package-lesson
    mkdir packages/testHelper/hasher/src -p
    
### 修改composer.json配置文件
    "autoload": {
        "classmap": [
            "database"
        ],
        "psr-4": {
            "App\\": "app/",
            "TestHelper\\Hasher\\": "packages/testHelper/hasher/src/"
        }
    },
    //重新生成autload文件
    composer dump-autoload
    
### 创建Md5Hasher类文件
    packages/testHelper/hasher/src
    
    <?php
    namespace TestHelper\Hasher;
    class MD5Hasher
    {
        public function make($value, array $options = [])
        {
            $salt = isset($options['salt']) ? $options['salt'] : '';
            return hash('md5', $value . $salt);
        }
        public function check($value, $hashValue, $options = [])
        {
            return $this->make($value, $options) === $hashValue;
        }
    }

### 生成provider （提供者）
    php artisan make:provider MD5HasherProvider
    
    cp app/Providers/MD5HasherProvider.php packages/testHelper/hasher/src
    
    修改
    packages/testHelper/hasher/src/MD5HasherProvider.php
    文件的命名空间为
    namespace TestHelper\Hasher;
    
### 修改注册函数
    vim packages/testHelper/hasher/src/MD5HasherProvider.php
    public function register()
    {
        //实现调用app('md5hash')，得到MD5Hahser实例
        $this->app->singleton('md5hash', function(){
            return new MD5Hasher();
        });
    }
### 配置
    vim config/app.php
    在providers数组中添加我们的Provider,即将下面内容加入其中
    TestHelper\Hasher\MD5HasherProvider::class,
    
    
### 测试
    php artisan tinker
    >>> app('md5hash')->make('12345', ['salt'=>121]);
    => "9f7095ad6ff1ad82bc5154466a48136d"
    
## 将package发布到packagist.org
### 设置composer配置文件
    cd packages/testHelper/hasher
    composer init 
    
    //将下面内容添加到刚刚生成的composer.json文件
    "autoload": {
        "psr-4": {
            "TestHelper\\Hasher\\": "src/"
        }
    }
### 初始化git配置文件
    git init
    git add .
    git commit -m 'init project'
    git remote add origin https://github.com/baoniu/TestLaravelPackage.git
    git push -u origin master
    
### 配置packages token
    去到github项目页面
    https://github.com/baoniu/TestLaravelPackage
    点击 setting 
    -> Integrations&services
    -> Add services
    -> Packagist
    
    填写Packagist.org上的User和Token信息
    https://packagist.org/profile/ 查看Token
    
### 提交到packagist.org
    https://packagist.org/packages/submit
    填写项目的github克隆地址
    https://github.com/baoniu/TestLaravelPackage.git
    点击Check -> Submit
    
    现在其它开发者可以使用composer下载你的包了

### 更新版本，自动同步到packagist.org
    git add .
    git commit -m '....'
    git push
    
    git tag 1.0 -a
    git push --tags

## 使用PHPUnit为Package编写单元测试
### 添加phpunit
    vim packages/testHelper/hasher/src/composer.json
    将下面内容添加到该文件
    "require-dev": {
        "phpunit/phpunit": "5.5.*"
    },
    
    更新
    composer update
### 创建测试用例
    mkdir packages/testHelper/hasher/src/tests
    touch packages/testHelper/hasher/src/tests/MD5HasherTest
    
    <?php
    use PHPUnit\Framework\TestCase;
    class MD5HasherTest extends TestCase
    {
        protected $hasher;
        public function setUp()
        {
            $this->hasher = new \TestHelper\Hasher\MD5Hasher();
        }
        public function testMD5HasherMake()
        {
            $password = md5('password');
            $passwordTwo = $this->hasher->make('password');
            $this->assertEquals($password, $passwordTwo);
        }
    }
    
### 测试
    vendor/bin/phpunit tests/MD5HasherTest
    
    
    