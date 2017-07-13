<?php

require_once("image_reduction.php");

/****************************************************************************/
/*画像アップロード関連
/****************************************************************************/
function flash_uplord_picture_management(){
	/*if($_POST['sendpost'] =="アップロード" )
		flash_uplord_picture_processing();*/

	flash_uplord_picture_form();
	
}
/****************************************************************************/
/*画像アップロードファーム
/****************************************************************************/
function flash_uplord_picture_form(){
	global $image_url_list;
?>
	<div class ="wrap">
		<div id="" class="clearfix">
			<div id="icon-options-general" class="icon32"></div>
			<h2>WP Simple Slideshow</h2>
		</div>
<?php
		if($_POST['sendpost'] =="アップロード" ){
			flash_uplord_picture_processing();
		}

		if(count($image_url_list) <= 50){
		?>
			<form method="post" enctype="multipart/form-data">
				<table>
					<tr>
						<th>
						アップロードしたい画像データを選択してください。(アップロードできる枚数は50枚までです)
						</th>
						<td>
							<input type='file' name='picfile' size ='30'>
						</td>
					</tr>
					<tr>
						<th>
						</th>
						<td>
							<input type='submit' name='sendpost' value='アップロード' class="button-primary" />
						</td>
					</tr>
				</table>
			</form>
		<?php
		}else{echo "既に５０枚アップされています。新しくアップする場合は古い画像を削除してください。";}
?>
	</div>
<?php
}
/****************************************************************************/
/*画像アップロード処理
/****************************************************************************/
function flash_uplord_picture_processing(){
	global $image_reduction,$image_url_list,$wpss_updir;

	$updir = wp_upload_dir();
	$updir = $updir['basedir'];
	
	if(count($image_url_list) <= 50){
		if($_FILES['picfile']['name'] ==""){
			print("<div class ='wrap'>");
			print("<span style='color:#f00;'>画像選択されていませんでした。もう一度選択してください。</span>");
			print("</div>");
	
			flash_uplord_picture_form();
			return; //end
		}
		
		$tmp_ary = explode('.',$_FILES['picfile']['name']);
		$tmp_ary = $tmp_ary[count($tmp_ary)-1];
		
		$img_type = array('jpg','jpeg','JPG','png');
		if(array_search($tmp_ary,$img_type) >= 0){
			//指定されたディレクトリなかったら作成
			if(!is_dir($wpss_updir)){
				$old = umask(0);
				@chmod($updir, 0775);
				$f = @mkdir($wpss_updir,0775);
				umask($old);
				if(!$f){
					echo $wpss_updir.'画像アップロード用のフォルダを作成できませんでした。wp-content/uploads に書き込み可能な権限を与えて下さい。';
					return ; //end
				}
			}
			//上書き中
			//if(!file_exists($updir .$_FILES['picfile']['name'])){
				if(@move_uploaded_file($_FILES['picfile']['tmp_name'], $wpss_updir.$_FILES['picfile']['name']) == false){
					echo  'アップロードエラー:';
					switch($_FILES['picfile']['error']){
						case 0:
							echo 'wp-content/uploads/に書き込み権限が有りません。';
							break;
						case 1:
							echo 'ファイルサイズが大きいです。';
							break;
					}
				}else{
					createfilelist_main();
					
					if(intval($image_reduction)){
						//画像を縮小するための関数
						image_reduction($wpss_updir .$_FILES['picfile']['name']);
					}
					print("<div class ='wrap'>");
					print("正常にアップロードが終了しました。");
					print("</div>");
				}
			/*}else{
				print("<div class ='wrap'>");
				print("<span style='color:#f00;'>同じ名前の画像ファイルがあるためアップロードできませんでした。</span>");
				print("</div>");
			}*/
		}else{
			echo "<span style='color:#f00;'>JPEGファイルのみアップロードできます</span>";	
		}
	}else{
		echo "<span style='color:#f00;'>アップロードできる枚数は５０枚までです。すでに５０枚アップされています。</span>";
	}
}
/*********************************************************************************/
/*ファイルリスト作成
/*********************************************************************************/
function createfilelist_main(){
	global $list_file_url, $createxml, $listfile_name, $imagefolder,$wpss_updir,$wpss_htmlpath;

	$cxml_len =strlen($createxml);
	$cxmlpass =substr($createxml, 2, $cxml_len);//
	$folderpass = substr($imagefolder,2);
	
	$get_db_list = get_option('wp_simple_slideshow_image_list');
	array_push($get_db_list,$wpss_htmlpath.$_FILES['picfile']['name']);
	update_option('wp_simple_slideshow_image_list',$get_db_list);
}
?>