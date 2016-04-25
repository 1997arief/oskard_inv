<?php
/**
* @author Arief M. Ikhsan
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue('application', 'docroot').'module/rtInventaris/business/mysqlt/Latihan.class.php';
//error_reporting(E_ALL & ~E_WARNING);
class ViewPinjamInventaris extends HtmlResponse {
	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/rtInventaris/template');
		$this->SetTemplateFile('view_pinjam_inventaris.html');
	}
	
	function Processrequest() {
		$msg = Messenger::Instance()->Receive(__FILE__);
		if($msg) {
			$return['pesan'] = $msg[0][1];
			$return['css'] = $msg[0][2];
		} else {
			$return['pesan'] = null;
			$return['css'] = null;
		}
		$ObjInv = new Latihan();

		$itemViewed = 10;
		$currPage = 1;
		$startRec = 0;
		if(isset($_GET['page'])) {
			$currPage = (string) $_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec = ($currPage - 1) * $itemViewed;
		}

		$invId = Dispatcher::Instance()->Encrypt($_GET['invId']->Raw());
		if(!empty($invId)) {
			$return['dataInventaris'] = $ObjInv->GetInventarisById($invId);
			$return['dataPinjam']=$ObjInv->GetJumlahPinjam($invId);
			$return['dataPeminjam']=$ObjInv->GetPeminjam($invId,$startRec, $itemViewed);
			$totalData = $ObjInv->GetHitungPeminjam($invId);
		}

		//var_dump($ObjInv->GetJumlahPinjam($invId));
		//exit;
		$url = Dispatcher::Instance()->GetUrl(
		Dispatcher::Instance()->mModule,
		Dispatcher::Instance()->mSubModule,
		Dispatcher::Instance()->mAction,
		Dispatcher::Instance()->mType).'&invId='.Dispatcher::Instance()->Encrypt($invId);
		
		$destination_id = "subcontent-element";
		
		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed, $totalData, $url, $currPage, $destination_id), Messenger::CurrentRequest);
		$return['start_number'] = $startRec+1;
		$return['inv_id'] = $invId;
		return $return;

	}
	
	function ParseTemplate ($data = NULL) {
		if($data['pesan']!="") {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
		}
		
		if(!empty($data['inv_id'])) {
			if ($data['dataPinjam'][0]['jumlah']!= NULL) {
				$jmlPinjam = $data['dataPinjam'][0]['jumlah'];
			} else {
				$jmlPinjam = 0;
			}
			$jmlSisa = $data['dataInventaris'][0]['inv_jml']-$jmlPinjam;
			//$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('rtInventaris', 'UbahInventaris', 'do', 'json'));
			$this->mrTemplate->AddVar('content', 'INV_NAMA', $data['dataInventaris'][0]['inv_nama']);
			$this->mrTemplate->AddVar('content', 'INV_PEMILIK', $data['dataInventaris'][0]['inv_pemilik']);
			$this->mrTemplate->AddVar('content', 'INV_JML', $data['dataInventaris'][0]['inv_jml']);
			$this->mrTemplate->AddVar('content', 'INV_KET', $data['dataInventaris'][0]['inv_ket']);
			$this->mrTemplate->AddVar('content', 'PINJAM_JUMLAH', $jmlPinjam);
			$this->mrTemplate->AddVar('content', 'SISA_JUMLAH', $jmlSisa);
			$this->mrTemplate->AddVar('content', 'INV_ID', $data['inv_id']);
			$this->mrTemplate->addVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'ListLatihanKu', 'view', 'html'));
			

			if ($jmlSisa > 0) {
				$this->mrTemplate->AddVar('jml_sisa_cek', 'SISA_EMPTY', 'NO');
				$this->mrTemplate->Addvar('jml_sisa', 'URL_ADD', Dispatcher::Instance()->GetUrl('rtInventaris', 'TambahPeminjam', 'View', 'html').'&invId='.Dispatcher::Instance()->Encrypt($data['inv_id']).'&sisaJml='.Dispatcher::Instance()->Encrypt($jmlSisa));
			} else {
				$this->mrTemplate->AddVar('jml_sisa_cek', 'SISA_EMPTY', 'YES');
			}
			
		}

		if (!empty($data['dataPeminjam'])) {
			$this->mrTemplate->AddVar('data_peminjam_cek', 'DATA_EMPTY', 'NO');
			$no = $data['start_number'];
			/*
			$bulan = array(1=>"Januari",2=>"Februari",3=>"Maret"
					,4=>"April",5=>"Mei",6=>"Juni",7=>"Juli"
					,8=>"Agustus",9=>"September",10=>"Oktober"
					,11=>"November",12=>"Desember");
			*/
			foreach ($data['dataPeminjam'] as $key => $value) {
				$value['no'] = $no;
				if ($value['pinjam_kondisi']=="B") {
					$value['pinjam_kondisi']="Baik";
				} else {
					$value['pinjam_kondisi']="Rusak";
				}

				if ($value['pinjam_status']=="kembali") {
					$this->mrTemplate->AddVar('data_peminjam', 'KEMBALI', 'style="background-color:#BBB;"');
					$this->mrTemplate->AddVar('data_peminjam', 'UBAH', 'style="visibility: hidden;"');
				}
				$value['URL_UPDATE'] = Dispatcher::Instance()->GetUrl('rtInventaris', 'UbahPeminjam', 'view', 'html').'&pinjamId='.Dispatcher::Instance()->Encrypt($value['pinjam_id'].'&sisaJml='.Dispatcher::Instance()->Encrypt($jmlSisa));
				$value['row_class'] = $no%2 == 0?'even':'odd';
				$value['pinjam_tgl'] = date("d M Y",strtotime($value['pinjam_tgl']));
				$this->mrTemplate->AddVars('data_peminjam', $value);
				$this->mrTemplate->parseTemplate('data_peminjam', 'a');
				$no++;
			}
		} else {
			$this->mrTemplate->AddVar('data_peminjam_cek', 'DATA_EMPTY', 'YES');

		}
	}
}
?>