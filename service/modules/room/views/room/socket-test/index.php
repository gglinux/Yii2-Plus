<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>Socket</title>
    <link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap.min.css">
    <style type="text/css">
        .rtc-video-push,.rtc-video-pull {
            width: 90%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container" >
        
    </div>
    
</body>
<script type="text/javascript" src="/jquery/jquery.min.js"></script>

<script type="text/javascript" src="/bootstrap/js/bootstrap.min.js"></script>
<script src="/socket/socket.io.min.js"></script>
<script type="text/javascript">
    window.socket = io.connect('//192.168.11.11:4443/room?token=<?=$token ?>');

    socket.on('connect', function(o){
        console.log('socket id', socket.id); 

        //socket.emit('joinRoom',{});
    });
    socket.on('disconnect', function(o){
        console.log('disconnect'); 
    })
  

    socket.on('message', function(o){
        console.log('recive message msg', o);
        if(o.cmd == 'readyRoom') {
            socket.emit('message', {
                cmd: 'joinRoom',
                roomId: o.data.roomId
            })
        }
        
    });

    socket.on('broadcast', function(o){
        console.log('recive broadcast msg', o); 
      
    })

    socket.on('system', function(o){
        console.log('recive system msg', o); 
       
    })


    
    
</script>


</html>