
<!DOCTYPE html>
	<html>
	<head>
		<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
		<link rel="stylesheet" href="../resource/css/message.css"> 
	</head>
	<body>
		<!--留言板的头部数据-->
		<div class="message-header">
			<nav class="navbar navbar-default" role="navigation">
			  <div class="container-fluid">
			    <div class="navbar-header">
			      <a class="navbar-brand" href="#">留言板</a>
			    </div>
			    <ul class="nav navbar-nav navbar-right">
			    	<?php 
						if($loginuser != '')
							echo "<li class='logined'><a href='../logout''><span class='glyphicon glyphicon-user' style='margin-right:5px;'></span>".$loginuser."</a></li>";
						else
							echo "<li class='notlogin'><a href='../login/''><span class='glyphicon glyphicon-log-in'></span> 登录</a></li>";
					?>			     
			    </ul>
			  </div>
			</nav>
		</div>
		<div style='clear:both;'></div>
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
					<span>本留言总数：</span><span><?php echo count($messageList); ?></span><br/>
				</div>
			</div>
			<div class="message-right">
				<!-- 渲染该用户的留言板的所有的信息-->
				<div class="message-right-all">
					<ul class="list-group">

						<?php 
							if(count($messageList) == 0){

								echo "<div class='nomessage'>暂时还没有留言哦</div>";
							}

							foreach ($messageList as $key => $value) {

						?>
							<li class="list-group-item message-item message-item<?php echo $value['id']; ?>" style="min-height:80px;">

								<?php echo $value['content']; ?>

								<div class="message-item-bottom">
									发表人：<span><?php echo $value['username']; ?></span>
									发表时间:<span><?php echo $value['createdate']; ?></span>
									<?php 
										if($loginuser == $value['username']){

											echo "<span class='delete' messageid=".$value['id'].">删除</span>";
										}
									?>
								</div>
								
							</li>
						<?php
							}
						?>			
					</ul>
					
				</div>
				<?php 
					if($loginuser != ''){
				?>
						<!--增加留言的面板-->
						<div class="message-right-add">
							<textarea placeholder="Please enter a comment…"></textarea>
							<div class="submit">
								<button type="button" class="btn btn-primary">提交</button>
								<div class="addloading"></div>
								<div class="addLoadingImg"><img src="../resource/img/loginright.png"/></div>
							</div>

						</div>
				<?php
					}else{

				?>
					<!--增加未登录用户的提示区域-->
					<div class="message-add-nologin">
						
						<div class="message-add-nologinText"><a href='../login/'>登录</a>可以留言</div>
					</div>

				<?php
				}
				?>
					
			</div>

		</div>
		<!-- 用于消息提醒的模态框 -->
		<div class="modal fade" id="remindModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-dialog">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		                <h4 class="modal-title" id="myModalLabel">提示</h4>
		            </div>
		            <div class="modal-body" style="min-height:225px;">
		            	<div class="remindMessage" style="margin-left: 170px;margin-top:80px;">
			            	<div class="modalImg"></div>
			            	<div class="modalText">是否确定删除本条留言？</div>
			            	<div style="clear:both;"></div>
			            </div>
			            <div class="modal-loading">
			            	<div class="spinner">
							  <div class="double-bounce1"></div>
							  <div class="double-bounce2"></div>
							  <img src="../resource/img/right.png" style="width:60px;">
							</div>

			            </div>
		        	</div>
		            <div class="modal-footer remindMessage">
		                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
		                <button type="button" class="btn btn-primary remindBtn">确定</button>
		            </div>
		        </div><!-- /.modal-content -->
		    </div><!-- /.modal-dialog -->
		</div>
		<!-- /.modal -->
		<script type="text/javascript" src="../resource/js/jquery-2.1.1.min.js"></script> 
		<script type="text/javascript" src="../resource/js/bootstrap.min.js"></script> 
		<script>
			
			/**
				用户的删除操作
			*/
			$(".message-item-bottom > .delete").click(function(){

				var messageid = $(this).attr("messageid");

				$(".remindBtn").attr("messageid",messageid);

				$(".remindMessage").show();

				$(".modal-loading").hide();

				$("#remindModal").modal("show");
				
			});

			$(".remindBtn").click(function(){

				//$("#remindModal").modal("hide");

				//对于加载中信息初始化

				$(".modal-loading img").hide();

		    	$(".spinner div").show();

				$(".remindMessage").hide();

				$(".modal-loading").show();

				var messsageid = $(this).attr("messageid");

				delMessage(messsageid);

			});

			function delMessage(messageid){

				$.ajax({

						type: "POST",         

		    			url: "./delete",         

		    			dataType: "json",         

		    			data: {messageid: messageid},

		    			success: function(result){

		    				console.log(result);

		    				if(result.message == 1){

		    					$(".modal-loading img").show();

		    					$(".spinner div").hide();

		    					$(".message-item"+messageid).remove();
		    					//alert("删除成功");
		    					setTimeout(function(){

		    						$("#remindModal").modal("hide");
		    						//window.location.reload();

		    					},800)
		    					

		    				}

		    			} 


				});

			}

			/*增加留言的信息*/
			$(".message-right-add .btn").click(function(){

				var inputText = $(".message-right-add textarea").val();

				var receiveid = $(".message-left-info span").attr("userid");

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

		    			beforeSend : function(){

		    				$(".addloading").show();
		    				$(".message-right-add .btn").hide();
		    			},

		    			success : function(result){

		    				console.log(result);

		    				if(result.message == 1){

		    					$(".addLoadingImg").show();

		    					$(".addloading").hide();

		    					//alert("保存成功");

		    					window.location.reload();

		    				}else{

		    					$(".addloading").hide();
		    					$(".message-right-add .btn").show();

		    				}

		    			},
		    			complete : function(){

		    				// $(".addloading").hide();
		    				// $(".message-right-add .btn").show();

		    			}



		    		});

			});

		</script>
		
	</body>
</html>