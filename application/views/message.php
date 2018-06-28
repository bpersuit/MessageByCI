
<!DOCTYPE html>
	<html>
	<head>
		<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
		<link rel="stylesheet" href="../resource/css/message.css"> 
	</head>
	<body>
		<!--针对不同用户的显示留言板页面-->
		<div class="message-main">
			<!--主要用于显示被访问人的基本信息-->
			<div class="message-left">
				<div class="message-left-title">个人资料</div>
				<div class="message-left-info">
					<img src="../resource/userimg/bpersuit.jpg"/>
					<span userid= <?php echo $user['id'] ?>><?php echo $user['username']; ?></span>
				</div>
				<div class="message-left-show">
					<span>本留言总数：</span><span>20</span><br/>
					<span>发表留言数：</span><span>20</span>
				</div>
			</div>
			<div class="message-right">
				<!-- 渲染该用户的留言板的所有的信息-->
				<div class="message-right-all">
					<?php 
						if(count($messageList) == 0){

							echo "<div class='nomessage'>暂时还没有留言哦</div>";
						}

						foreach ($messageList as $key => $value) {

					?>
						<div class="message-item">

							<div class="message-item-content"><?php echo $value['content']; ?></div>

							<div class="message-item-bottom">
								发表人：<span><?php echo $value['username']; ?></span>
								发表时间:<span><?php echo $value['createdate']; ?></span>
								<?php 
									if($loginuser == $value['username']){

										echo "<span class='delete' messageid=".$value['id'].">删除</span>";
									}
								?>
							</div>
							<div style="clear:both;"></div>
						</div>
					<?php
						}

					?>

				</div>
				<!--增加留言的面板-->
				<div class="message-right-add">
					<textarea placeholder="Please enter a comment…"></textarea>
					<div class="submit">
						<button type="button" class="btn btn-primary">提交</button>
					</div>

				</div>

			</div>

		</div>
		<script type="text/javascript" src="../resource/js/jquery-2.1.1.min.js"></script> 
		<script type="text/javascript" src="../resource/js/bootstrap.min.js"></script> 
		<script>
			
			/**
				用户的删除操作
			*/
			$(".message-item-bottom > .delete").click(function(){

				var confirmResult = confirm("是否删除");

				var messageid = $(this).attr("messageid");

				if(confirmResult){

					$.ajax({

						type: "POST",         

		    			url: "./delete",         

		    			dataType: "json",         

		    			data: {messageid: messageid},

		    			success: function(result){


		    				if(result == 1)

		    					alert("删除成功");

		    			} 


					});
				}
			});

			/*增加留言的信息*/
			$(".message-right-add .btn").click(function(){


				var inputText = $(".message-right-add textarea").val();

				var receiveid = $(".message-left-info span").attr("userid");

				alert(receiveid);

				if(inputText == ""){

					alert("请输入评论信息");

					return;
				}

				$.ajax({

						type: "POST",         

		    			url: "./addMessage",         

		    			dataType: "json",         

		    			data: {inputText: inputText,
		    				receiveid : receiveid},

		    			success : function(result){

		    				console.log(result);

		    				if(result == 1){

		    					alert("保存成功");

		    					window.location.reload();

		    				}

		    			}



		    		});

			});

		</script>
		
	</body>
</html>