<?php
require_once('config.php');
$case = $_GET['case'];
require_once('function.php');
require_once(TEMPLATE.'header.php');

?>

<style type="text/css">
	.go-top{display:none;}
</style>

<div class="mcontent br_main"> 
	<div class="container-fluid">
		<div class="upper_sec">
			<div class="container">
				<div class="br-left-bar col-xs-offset-1 col-xs-5">
					<img src="<?=IMG_PATH_ENTER?>BrowserSupport_Img.png"/>
				</div>
				<div class="br-right-bar col-xs-5">
					<h2>OOPS!</h2>
					<h4>we don't support this Browser Currently</h4>
					<h4><b>Please Upgrade Your Browser</b></h4>
				</div>
			</div>
		</div>
		</div>
		
		<div claSS="clearfix"></div>
		<div class="container">
		<div class="br_wrapper">
			<h3>YOU NEED TO UPGRADE YOUR BROWSER TO ENJOY OUR LATEST FEATURES</h3>
			<img src="tools/img/divider.png"/>
			<div claSS="clearfix"></div>			
			
			<div class="br_wrp_cntnt">
				<div class="col-xs-4">
					<a href="http://www.opera.com/computer/windows" title="Opera" class="br_opera br_box">
					<img src="<?=IMG_PATH_ENTER?>opera.png"/>
					<div class="br_cont">
						<label class="br_dash">-</label> Organize bookmarks<br/>
						<label class="br_dash">-</label> Enjoy a fast browser experience<br/>
						<label class="br_dash">-</label> Many ways personalize<br/>
						<span class="br_link">Opera</span>
					</div>					
					</a>
				</div>
					
				<div class="col-xs-4">
					<a href="https://www.mozilla.org/en-US/firefox/new/" title="firefox" class="br_firefox br_box">
					<img src="<?=IMG_PATH_ENTER?>firefox.png"/>
					<div class="br_cont">
						<label class="br_dash">-</label> Real-time communication client<br/>
						<label class="br_dash">-</label> Improved User Interface<br/>
						<label class="br_dash">-</label> Faster web browser<br/>
						<span class="br_link">Mozilla Firefox</span>
					</div>					
					</a>
				</div>
				
				<div class="col-xs-4">
					<a href="https://www.google.com/intl/en_in/chrome/browser/desktop/index.html" title="Google Chrome" class="br_chrome br_box">
					<img src="<?=IMG_PATH_ENTER?>chrome.png"/>
					<div class="br_cont">
						<label class="br_dash">-</label> Search instantly<br/>
						<label class="br_dash">-</label> Type less<br/>
						<label class="br_dash">-</label> Pick up where you left off<br/>
						<span class="br_link">Google Chrome</span>
					</div> 					
					</a>
				</div>
				
				<div class="col-xs-6 col-xs-offset-1">
					<a href="http://support.apple.com/kb/dl1531" title="Safari" class="br_safari br_box">
					<img src="<?=IMG_PATH_ENTER?>safari.png"/>
					<div class="br_cont">
						<label class="br_dash">-</label> Most innovative browser<br/>
						<label class="br_dash">-</label> Less complicated<br/>				
						<span class="br_link">Apple Safari</span>
					</div>					
					</a>
				</div>
				 
				<div class="col-xs-2 br_internet">
					<a href="http://windows.microsoft.com/en-IN/internet-explorer/download-ie" title="Internet Explorer" class="br_box">
					<img src="<?=IMG_PATH_ENTER?>IE.png"/>
					<div class="br_cont">
						<label class="br_dash">-</label> control your privacy<br/>
						<label class="br_dash">-</label> Free from Microsoft<br/>
						<span class="br_link">Internet Explorer</span>
					</div>					
					</a>
				</div>
			</div>
			
		</div>
		
	</div>
	
	<div class="clearfix"></div>

	<div class="footer">
		<div id="footer" class="container">
			<div class="row">
				<div>&copy; 2008-15. <span class="j-colr">Just</span><span class="d-colr">dial.com</span> All Rights Reserved.</div>						
			  </div>
		</div>
	</div>	
</div>	