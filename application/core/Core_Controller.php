<?php
class Core_Controller extends CI_Controller
{
    static $request     = NULL;
    public $uriString   = '';
    public $uriArray    = array();

    static $classs      = array();
    static $traceLog    = NULL;
    static $di          = array();

    private $usersId    = 88;

    public $viewConf = array(
        'footer' => 'footer',
        'header' => 'header'
    );

    /**
     * Core_Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        static::$request = $this->input;

        # 是否开启线上debug模式
        $isDebug = self::get('is_debug', 1, 'boolval');
        $this->isDebug($isDebug);

        # 权限鉴定
        $this->authorization($project = config_item('project'));
    }

    /**
     * 权限验证规则
     * @param string $platform
     * @author xuyi
     * @date 2019/10/31
     */
    public function authorization($project = 'api')
    {
        if($project !== 'api') {
            return successMsg('不需要权限验证');
        }

        $platform    = self::get('platform', 'pc', 'trim');
        $requestTime = self::get('request_time', 0, 'intval');
        $version     = self::get('version', '1.02', 'trim');
        $sign        = self::get('sign', '', 'trim');

        $mimVersion  = '1.20'; # 最小可用版本

        # 执行验证
        $validate = array(
            ''
        );
        $this->load->driver('Driver_authorization');
        $result = $this->driver_authorization->drive($platform)->validate($validate)->exec();
        if($this->driver_authorization->authorization === FALSE) {
            $this->displayJson($result);
        }

        return successMsg('权限验证捅过');
    }

    /**
     * 是否开启debug模式
     * @param bool $isDebug
     * @author xuyi
     * @date 2019/10/31
     */
    public function isDebug($isDebug = TRUE)
    {
        if($isDebug == TRUE) {
            self::$traceLog  = self::classes('TRACE_info', 'drive'); # 页面运行信息&错误记录
            $this->output->enable_profiler(1);
        }
    }

    /**
     * 获取登录用户UsersId
     * @author xuyi
     * @date 2019/9/21
     * @return int
     */
    public function getLoginUsersId()
    {
        $signal  = get_cookie('identifier', TRUE);
        $signal  = trim($signal);

        if(! empty($this->usersId)) {
            return $this->usersId;
        }

        if(! empty($signal))
        {
            $this->cache($adapter = 'redis', $backup = 'redis');
            $usersSignalKey = $this->cache->key(USERS_SIGNAL_USERS_ID_KEY, $signal);
            $this->usersId  = intval($this->cache->get($usersSignalKey));
        }

        return $this->usersId;
    }

    /**
     * @return array|null|object
     */
    public function trace()
    {
        return self::$traceLog;
    }

    /**
     * RPC function , callback Server model
     * @param null $group | model group dir
     * @param null $model | model file name
     * @return null
     */
    public function server($class = NULL, $group = NULL, $domainName = NULL)
    {
        $domainName = trim($domainName);
        $group = trim($group);
        $class = trim($class);
        if(empty($group) || empty($class)) {
            return NULL;
        }

        $modelNameString = $group .'/'. $class;

        $this->load->library('CI_Rpc', FALSE, 'server');

        return $this->server->Model($modelNameString);
    }

    /**
     * 缓存
     * @param string $type
     * @return null
     */
    public function cache($adapter = NULL, $backup = NULL)
    {
        $adapter = trim($adapter);
        $adapter = empty($adapter) ? 'dummy' : $adapter;
        $backup  = trim($backup);
        $backup  = empty($backup) ? 'dummy' : $backup;


        # 初始化默认缓存配置
        $this->load->driver('cache');

        $this->cache->adapter($adapter); # 首选缓存方式
        $this->cache->backup($backup);   # 备选缓存方式

        $this->cache->initialize();
        return $this->cache;
    }


    /**
     * model function , get models dir model class file object
     * @param string $name
     * @param null $group
     */
    public function model($name, $group = NULL)
    {
        $group = trim($group);
        $name  = strtolower(trim($name));
        $modelNameString = strtolower($group) .'/'. $name;
        $modelNameString = ltrim($modelNameString, '/');


        $this->load->model($modelNameString, $name, FALSE);
        return $this->$name;
    }

    /**
     * HTTP URL POST Param gets
     *
     * @param $name
     * @param null $default default value string
     * @param null $filter  filter callback function
     * @return mixed
     */
    public static function post($name = NULL, $default = NULL, $filter = 'htmlspecialchars')
    {
        $value = static::$request->post($name, TRUE);
        if(empty($value)) {
            $value = $default;
        }

        return empty($filter) || is_array($value) ? $value : call_user_func($filter, $value);
    }

    /**
     * HTTP URl GET  Param gets
     *
     * @param $name
     * @param null $default
     * @param string $filter
     * @return null
     */
    public static function get($name = NULL, $default = NULL, $filter = 'htmlspecialchars')
    {
        $value = static::$request->get($name, TRUE);
        if(empty($value)) {
            $value = $default;
        }

        return empty($filter) || is_array($value) ? $value : call_user_func($filter, $value);
    }

    /**
     * get or post param string
     *
     * @param $name
     * @return mixed|null|string
     */
    public function paramAll($name)
    {
        $getParam = $this->get($name);
        $name = trim($name);
        return ! empty($name) ? (empty($getParam) ? $this->post($name) : $getParam) : '';
    }

    /**
     * isGet
     * @param null $method
     * @param null $class
     * @param array $params
     * @return bool|mixed
     */
    public static function isGet($paramArr = array())
    {
        $bool = $_SERVER['REQUEST_METHOD'] == 'GET';
        $params = is_array($paramArr) ? $paramArr : array($paramArr);
        return $bool;
    }

    /**
     * @param null $method
     * @param null $class
     * @param array $params
     * @return bool|mixed
     */
    public static function isPost($paramArr = array())
    {
        $bool = $_SERVER['REQUEST_METHOD'] == 'POST';
        $params = is_array($paramArr) ? $paramArr : array($paramArr);
        return $bool;
    }

    /**k
     * load class file
     *
     * @param $name
     * @param null $group
     * @param array $paramArr
     * @return array|object
     */
    public static function classes($name, $group = NULL, array $paramArr = array())
    {
        $name   = strtolower(trim($name));
        $group  = 'class/' . strtolower(trim($group));

        if(empty($name)) {
            return array();
        }

        $diIoc   = $group . $name . md5(serialize($paramArr));
        $inArray = in_array($group, config('config','di_ioc'));
        if(isset(static::$classs[$diIoc]) && ! empty(static::$classs[$diIoc]) && $inArray) {
            return static::$classs[$diIoc];
        }

        $classes = load_class($name, $group, $paramArr, FALSE, TRUE);
        static::$classs[$diIoc] = $classes;

        return $classes;
    }


    /**
     * display json data view
     *
     * @param array $param
     * @param null $ttl
     */
    public function displayJson($param = '', $ttl = NULL)
    {
        header("Content-type: application/json; charset=utf-8", TRUE);

        isAllowOrigin(TRUE); # CORS

        $ttl = empty($ttl) ? config('webcache', 'cache_time') : $ttl;
        $cacheType = config('webcache', 'cache_type');

        $paramArr['get'] = self::get();

        // 支持JSONP
        $JSONP = ! isset($paramArr['get']['callback']) ? '' : trim($paramArr['get']['callback']) | $paramArr['get']['callback'] = '';

        if(! is_array($param) && is_object($param))
        {
            BrowserCache($ttl);

            $serialize = serialize(array(
                'paramArr'  =>  $paramArr,
                'ttl'       =>  $ttl,
                'url'       =>  $this->urlPath()->uriArray
            ));

            $tempJson = md5('json('.$ttl.')param:' . md5($serialize));
            $Json = $this->cache($cacheType)->get($tempJson);

            if(empty($Json))
            {
                $Json = $param();
                $this->cache($cacheType)->save($tempJson, $Json, $ttl);
            }

            $param = $Json;
        }

        $JSON = json_encode($param, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        exit(empty($JSONP) ? $JSON : $JSONP . "({$JSON})");
    }

    /**
     * 公共底部方法
     *
     * @param null $themes
     * @return mixed
     */
    protected function footer($themes = NULL, $layouts = NULL, $data = array())
    {
        $themes = empty($themes) ? themes(config('config', 'theme_views_path')) : trim($themes);
        $layouts = empty($layouts) ? config('config','theme_views_layouts') : trim($layouts);

        # 设置页面公共footer
        $footer = config('config','theme_views_footer');
        $footerView = trim($this->viewConf['footer']);
        $footer = isset($this->viewConf['footer']) && ! empty($footerView) ? $this->viewConf['footer'] : $footer;
        $footer = $themes . $layouts . '/' . $footer;

        return $this->load->view($footer, $data, TRUE);
    }

    /**
     * view header show
     *
     * @param null $themes
     * @param null $layouts
     * @return mixed
     */
    protected function header($themes = NULL, $layouts = NULL, $data = array())
    {
        $themes = empty($themes) ? themes(config('config', 'theme_views_path')) : trim($themes);
        $layouts = empty($layouts) ? config('config', 'theme_views_layouts') : trim($layouts);

        # 设置页面公共header
        $header = config('config', 'theme_views_header');
        $headerView = trim($this->viewConf['header']);
        $header = isset($this->viewConf['header']) && ! empty($headerView) ? $this->viewConf['header'] : $header;
        $header = $themes . $layouts . '/' . $header;

        return $this->load->view($header, $data, TRUE);
    }

    /**
     * view display show
     *
     * @param array $data
     * @param null $tpl
     */
    public function displayTpl($param = '', $ttl = NULL, $tpl = NULL)
    {
        $ttl = empty($ttl) ? config('webcache', 'cache_time') : $ttl;
        $tpl = empty($tpl) ? $this->urlPath()->uriString : $tpl;

        $themes =  themes(config('config', 'theme_views_path'));

        $layouts = config('config', 'theme_views_layouts');

        $tpl = $themes . $tpl; # 主题支持设置

        $cacheType = config('webcache', 'cache_type');

        if(! is_array($param) && is_object($param))
        {
            BrowserCache($ttl);

            $paramArr['get'] = self::get();

            $serialize = serialize(array(
                'paramArr'  =>  $paramArr,
                'ttl'       =>  $ttl,
                'url'       =>  $this->urlPath()->uriArray
            ));

            $tempTpl = md5('tpl('.$ttl.'):' . $tpl . ' param:' . md5($serialize));

            $Html = $this->cache($cacheType)->get($tempTpl);

            if(empty($Html))
            {
                $data = $param();

                error($data); # 错误处理

                $header = $this->header($themes, $layouts, $data);
                $body   = $this->load->view($tpl, $data, TRUE);
                $footer = $this->footer($themes, $layouts, $data);

                $Html = $header . $body . $footer;
                $this->cache($cacheType)->save($tempTpl, $Html, $ttl);
            }

            echo $Html;
            return TRUE;
        }

        echo $this->header($themes, $layouts);
        echo $this->load->view($tpl, $param, TRUE);
        echo $this->footer($themes, $layouts);

        return TRUE;
    }

    /**
     * get exec controller 、 dir 、 file name
     *
     * @return $this
     */
    public function urlPath()
    {
        $uri = array(
            'directory' => trim($this->router->fetch_directory(), '/'),
            'class' => strtolower(trim($this->router->fetch_class(), '/')),
            'method' => trim($this->router->fetch_method(), '/')
        );

        $uri = array_filter($uri);
        $this->uriArray  = $uri;
        $this->uriString = implode(DIRECTORY_SEPARATOR, $this->uriArray);
        return $this;
    }

    /**
     * 视图方法
     *
     * @return array|object
     */
    public function view()
    {
        $paramArr = array(
            'ttl' => config('webcache', 'cache_time'),
            'cache_type' => config('webcache', 'cache_type')
        );

        return static::classes('WEB_cache', 'drive', $paramArr);
    }


    /**
     * 注入需要的操作对象
     *
     * @param string $name
     * @param object $object
     * @param bool|FALSE $cover
     * @return object $this
     */
    public function di($name, $object, $cover = FALSE)
    {

        if(! empty(static::$di) && isset(static::$di[$name]) && ! $cover) {
            log_message('info', '"Di" list there are '.$name.'by name Object !');
        }

        if(! isset(static::$di[$name]) || $cover) {
            static::$di[$name] = $object;
        }

        return $this;
    }

    /**
     * 获取对象
     *
     * @param string $name
     * @return array
     */
    public function make($name = '')
    {
        $name = trim($name);

        if(! empty($name) && isset(static::$di[$name]))
        {
            return static::$di[$name];
        }

        return array();
    }
}
