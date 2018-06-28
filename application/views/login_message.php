<!DOCTYPE html>
	<html>
	<head>
		<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
		<link rel="stylesheet" href="../resource/css/index.css"> 
	</head>
	<body>
		<!-- 登录页面的主要显示区域 -->
		<div id="myAlert" class="alert alert-warning" style="display:none;color：red;">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<span></span>
		</div>
		<div id="login"> 
	      	<span class="login_header">用户登录</span>
	     	<div id="login_form">
	     		<div class="login-form-user">
		     		<span class="glyphicon glyphicon-user"></span>
		     	 	<input type="text" class="form-control" id="user" placeholder="用户名">
		     	 </div>
		     	 <div class="login-form-pass">
		     	 	<span class="glyphicon glyphicon-lock"></span>
		     	 	<input type="password" class="form-control" id="pass" placeholder="密码">
		     	 </div>
		     	 <div class="login-form-check">
		     	 	<span class="glyphicon glyphicon-check"></span>
		     	 	<input type="text" class="form-control code" placeholder="验证码">
	          		<img src='../resource/authcode.php' class="login_code"/>
		     	 </div>
	     	 
	         
	          <div class="login_submit">
		          <button type="button" class="btn btn-primary">登 录</button>
		          <div class="login-loading"></div>
		      </div>
	          
	      	</div> 
	    
		</div> 
		

		<script type="text/javascript" src="../resource/js/jquery-2.1.1.min.js"></script> 
		<script type="text/javascript" src="../resource/js/bootstrap.min.js"></script> 
		<script>
			
			$(function(){

				//登录的异步处理函数
				$(".btn").click(function(){

					var username = $("#user").val().trim();
					var pas = $("#pass").val().trim();
					var inputcode = $(".code").val().trim();

					//进行验证
					if(login_valication()){

						$.ajax({         

		    				type: "POST",         

		    				url: "../loginUser",         

		    				dataType: "json",         

		    				data: {"user":username,"pass":pas,"inputcode":inputcode}, 

		    				beforeSend : function(){

		    					$(".btn").hide();
		    					$(".login-loading").show();
		    				},        

		    				success:function(result){

		    					console.log(result);

		    					if(result.success == 0){

			    					$("#myAlert > span").text(result.message);

			    					$("#myAlert").show();
			    				}else{

			    					alert("登录成功");

			    					window.location.href = '../message/index?userid='+result.success;

			    				}

		    					//alert(result.message);

		    				},
		    				complete : function(){

		    					$(".btn").show();
		    					$(".login-loading").hide();
		    				}
		    			});
					}
				});

				$(".login_code").click(function(){


					$(".login_code").attr("src","../resource/authcode.php?ext="+Math.random());

					

				});
			})

			//前端对于输入内容的基本验证
			function login_valication(){

				var username = $("#user").val().trim();

				var pas = $("#pass").val().trim();

				var inputcode = $(".code").val().trim();

				var cookieCode = getCookie("code");

				if(username == null || username == '' ){

					alert("请输入用户名");

					return false;
				}
				if(pas == null || pas == '' ){

					alert("请输入密码");

					return false;

				}

				if(inputcode ==''){

					alert("请输入验证码");

					return false;

				} 
				if(cookieCode.toLowerCase() != inputcode.toLowerCase()){

					alert("验证码输入错误");

					return false;
				}

				//还需要对于相关的长度等等做限制验证

				return true;

			}
			//获取cookie数据
			function getCookie(cname){
			    var name = cname + "=";
			    var ca = document.cookie.split(';');
			    for(var i=0; i<ca.length; i++) {
			        var c = ca[i].trim();
			        if (c.indexOf(name)==0) { return c.substring(name.length,c.length); }
			    }
			    return "";
			}
		</script>
	</body>
</html>