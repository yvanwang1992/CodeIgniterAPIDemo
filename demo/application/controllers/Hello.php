<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'libraries/REST_Controller.php';

class ResultObj{
    public $resultcode;
    public $reason;
    public $result;
    public $error_code;
}

class Hello extends REST_Controller {
    
    /**
    * Index Page for this controller.
    *
    * Maps to the following URL
    * 		http://example.com/index.php/welcome
    *	- or -
    * 		http://example.com/index.php/welcome/index
    *	- or -
    * Since this controller is set as the default controller in
    * config/routes.php, it's displayed at http://example.com/
    *
    * So any other public methods not prefixed with an underscore will
    * map to /index.php/welcome/<method_name>
    * @see https://codeigniter.com/user_guide/general/urls.html
    */
    
	public function __construct()
    {
        parent::__construct();

        //手动连接数据库
        $this->load->database();  
    }
    
    // public function Index()
    // {
    //     //echo "Hello World";
	//     $data=array("greeting"=>"hello world");
    //     $this->load->view('hello_message',$data);  
    // }
 
    //模拟
    //要使用这种来定义get  post   push  delete
    function method_get()
    {
        // get
        $this->load->library('curl');
        $result = $this->curl->simple_get('http://op.juhe.cn/onebox/news/query');//('http://localhost/demo/index.php/hello?username=1144');
        //json_encode  可以将array和对象 转换为json
        //result必须是ntf-8编码
        //{"resultcode":"101","reason":"错误的请求KEY!","result":null,"error_code":10001}

        $resultObj = new ResultObj;
        $resultObj = json_decode($result);//转换为对象  默认为false
        echo $resultObj->reason;

        // $arr = json_decode($result, true);//true转换为字典
        // echo $arr['reason'];//获取reson

        if(isset($result->status) && $result->status == 'success')
        {
            echo $result;
        }
        else
        {
            echo $result;
        }
 
        
        //POST  
        //简单方式
        // $this->load->library('curl');
        // $postData = array('username'=>'3322', 'sex'=>'female','age'=>'55','height'=>'123');
	    // $result = $this->curl->simple_post('http://localhost/demo/index.php/hello/index',$postData);
        // if(isset($result->status) && $result->status == 'success')
        // {
        //     echo $result;
        // }
        // else
        // {
        //     echo $result;
        // } 


        //另一种方式        
        // $this->load->library('curl');
        // $postData = array('username'=>'990', 'sex'=>'female','age'=>'55','height'=>'123');
	    // $this->curl->create('http://localhost/demo/index.php/hello/index');
        // $this->curl->post($postData); 
        // echo $this->curl->execute(); 


    }
 
        
    
    function index_get($id = '')
    {
        //可接受多个参数
        if(empty($_GET['username'])){
            $this->response(array('error','UserName Can not be Empty'), 400);
        }

        //获取输入的username
        $username=$this->input->get('username', TRUE);

        $this->load->model('hello_model');
        $data=$this->hello_model->getAllData();
        //$data=$this->db->get('Student')->result();   //放到Model中去了    
        
        if(empty($data)){//获取数据失败
            $this->response(array('status'=>0, 'message'=>'Failed To Obtain Data', 'result'=>''));
        }else{//获取数据成功 
            $this->response(array('status'=>1, 'message'=>'Successed To Obtain Data', 'result'=>$data));
        }


        ///////////////////////////////////////////////////
    	// Example data for testing.
    	$widgets = array(
    			1 => array('id' => 1, 'name' => 'sprocket'),
    			2 => array('id' => 2, 'name' => 'gear')
    	);
    	 
        //获取请求中的参数ID
    	if (!$id) { $id = $this->get('id'); }
    	if (!$id)
    	{
            //如果有ID的话  使用widgets_model调用getWidgets()或者getWidget($id)
            //模型的返回值将会从父类的response()返回
    		//$widgets = $this->widgets_model->getWidgets();    		    		
    		if($widgets)
    			$this->response($widgets, 200); // 200 being the HTTP response code
    		else
    			$this->response(array('error' => 'Couldn\'t find any widgets!'), 404);
    	}
        
        //$widget = $this->widgets_model->getWidget($id);
    	$widget = @$widgets[$id]; // test code
        if($widget)
            $this->response($widget, 200); // 200 being the HTTP response code
        else
            $this->response(array('error' => 'Widget could not be found'), 404);
    }

    //测试
    // curl -d "username=221&age444&sex=male&height=100" http://localhost/dex.php/hello

    function index_post(){

        //获取请求的数据  父类通过Format.php对请求的数据进行处理并把它们放到了$this->_post_args里
        $data = $this->_post_args;
        // [name age]
        if(array_key_exists('username', $data)){ 
            $age = '0';
            $height='100';
            if(array_key_exists('age', $data))
                $age = $data['age'];
            else
                $age = 0;

            if(array_key_exists('height', $data))
                $height = $data['height'];
            else
                $height = 100.0;

            $this->load->model('hello_model');
            $result = $this->hello_model->InsertData($data['username'],$data['sex'],$age,$height);//DeleteData();
            $this->response(array('status'=>$result),200);
        }else{
            $this->response(array('status'=>'no name'),404);
        }



        try{
            //创建模型的数据
            //$id = $this->widgets_model->CreateWidget($data);
            $id = 3;
        }catch(Exception $e){
            $this->response(array('error'=>$e->getMessage()),$e->getCode());
        }

        if($id){
            //$widget = $this->widget_model->getWidget($id);
            $widget=array('id'=>$id, 'name'=>$data['name']);
            $this->response($widget, 201);
        }else{
            $this->response(array('error'=>'Widget could not be created'),404);
        }
    }
}