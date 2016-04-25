<?php

require_once Configuration::Instance()->GetValue('application', 'docroot').'module/rtInventarisPinjam/business/'.Configuration::Instance()->GetValue('application', 'db_conn',0,'db_type').'/Pinjam.class.php';

class ViewListPinjam extends HtmlResponse {

	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/rtInventarisPinjam/template');
		$this->SetTemplateFile('view_list_pinjam.html');
	}
	
	function ProcessRequest() {
		$msg = Messenger::Instance()->Receive(__FILE__);
		if($msg) {
			$return['pesan'] = $msg[0][1];
			$return['css'] = $msg[0][2];
		} else {
			$return['pesan'] = null;
			$return['css'] = null;
		}

		$Obj = new Pinjam();

		$itemViewed = 10;
		$currPage = 1;
		$startRec = 0;
		if(isset($_GET['page'])) {
			$currPage = (string) $_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec = ($currPage - 1) * $itemViewed;
		}
		$url = Dispatcher::Instance()->GetUrl(
		Dispatcher::Instance()->mModule,
		Dispatcher::Instance()->mSubModule,
		Dispatcher::Instance()->mAction,
		Dispatcher::Instance()->mType);

		$destination_id = "subcontent-element";


		$cariNama = '';
		$cariStatus = '';
		$cariKondisi = '';
		if (isset($_POST['btncari'])) {
			$cariNama = $_POST['cari_nama'];
			$cariStatus = $_POST['cari_status'];
			$cariKondisi = $_POST['cari_kondisi'];
		}
		$return['cariNama'] = $cariNama;
		$return['cariStatus'] = $cariStatus;
		$return['cariKondisi'] = $cariKondisi;

		$totalData = $Obj->GetCountPinjam($cariNama,$cariStatus,$cariKondisi);
		$return['dataPinjam'] = $Obj->GetDataPinjam($cariNama,$cariStatus,$cariKondisi, $startRec, $itemViewed);
		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed, $totalData, $url, $currPage, $destination_id), Messenger::CurrentRequest);
		$return['start_number'] = $startRec+1;

		return $return;
	}
	
	function ParseTemplate($data=NULL) {
		$this->mrTemplate->Addvar('content', 'URL_CARI', Dispatcher::Instance()->GetUrl('rtInventarisPinjam', 'ListPinjam', 'view', 'html'));

		if($data['pesan']!="") {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
		}

		$this->mrTemplate->AddVar('content', 'CARI_NAMA', $data['cariNama']);
		if ($data['cariStatus']=="pinjam") {
			$this->mrTemplate->AddVar('content', 'PINJAM_SELECTED', 'selected');
		} elseif ($data['cariStatus']=="kembali") {
			$this->mrTemplate->AddVar('content', 'KEMBALI_SELECTED', 'selected');
		}
		if ($data['cariKondisi']=="B") {
			$this->mrTemplate->AddVar('content', 'BAIK_SELECTED', 'selected');
		} elseif ($data['cariKondisi']=="R") {
			$this->mrTemplate->AddVar('content', 'RUSAK_SELECTED', 'selected');
		}
		
		if (!empty($data['dataPinjam'])) {
			$this->mrTemplate->AddVar('data_pinjam_cek', 'DATA_EMPTY', 'NO');
			$no=$data['start_number'];
			foreach ($data['dataPinjam'] as $key => $value) {
				$value['no'] = $no;
				if ($value['pinjam_kondisi']=="B") {
					$value['pinjam_kondisi']="Baik";
				} else {
					$value['pinjam_kondisi']="Rusak";
					$this->mrTemplate->AddVar('data_pinjam', 'R', 'color:red;');
				}
				if ($value['pinjam_status']=="kembali") {
					$this->mrTemplate->AddVar('data_pinjam', 'UBAH_DISABLE', 'hidden');
					$this->mrTemplate->AddVar('data_pinjam', 'BARANG_KEMBALI', 'background-color:#BBB;');
				}
				$value['URL_UPDATE'] = Dispatcher::Instance()->GetUrl('rtInventarisPinjam', 'UbahPinjam', 'view', 'html').'&pinjamId='.Dispatcher::Instance()->Encrypt($value['pinjam_id']).'&invId='.Dispatcher::Instance()->Encrypt($value['pinjam_inv_id']);
					$value['row_class'] = $no%2 == 0?'even':'odd';
					$label = "Peminjam";
                    $idEnc = Dispatcher::Instance()->Encrypt($value['pinjam_id']);
                    $dataName = Dispatcher::Instance()->Encrypt($value['pinjam_nama']);
                    $urlAccept = 'rtInventarisPinjam|HapusPinjam|do|json';
                    $urlReturn = 'rtInventarisPinjam|ListPinjam|view|html';
                    $value['URL_DELETE']=Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='.$urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
                    $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
				$value['pinjam_tgl'] = date("d M Y",strtotime($value['pinjam_tgl']));
				$this->mrTemplate->addVars('data_pinjam', $value);
		        $this->mrTemplate->parseTemplate('data_pinjam', 'a');
		        $no++;
			}
		} else {
			$this->mrTemplate->AddVar('data_pinjam_cek', 'DATA_EMPTY', 'YES');
		}
	}
}
?>