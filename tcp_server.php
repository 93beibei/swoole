<?php

//创建Server对象，监听 127.0.0.1:9501端口
$serv = new swoole_server("127.0.0.1", 9501);

//监听连接进入事件
$serv->on('connect', function ($serv, $fd) {
    echo "Client: Connect.\n";
});

//监听数据接收事件
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    $serv->send($fd, "Server: ".$data);
});

//监听连接关闭事件
$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.\n";
});

//启动服务器
$serv->start();
die;
class Server{

    private $serv;

    public function __construct()
    {
        $this->serv = new swoole_server("0.0.0.0" , 9501);
        $this->serv->set(
            array(
                'worker_num' => 8 ,
                'daemonize' => false
            )
        );

        $this->serv->on('Start' , array($this , 'onStart'));
        $this->serv->on('Connect' , array($this , 'onConnect'));
        $this->serv->on('Receive' , array($this , 'onReceive'));
        $this->serv->on('Close' , array($this , 'onClose'));

        $this->serv->start();
    }



    public function onStart($serv)
    {
        echo "Start\n";
    }



    public function onConnect($serv , $fd , $from_id)
    {
        $serv->send($fd , "Hello {$fd}!");
    }


    public function onReceive(swoole_server $serv , $fd  , $from_id , $data)
    {
        echo "Get Message From Client {$fd}:{$data}\n";
        $serv->send($fd , $data);
    }

    public function onClose($serv , $fd , $from_id)
    {
        echo "Client {$fd} close connection\n";
    }

}

$server = new Server();
