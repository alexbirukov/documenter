<?php

	require_once(TOOLKIT . '/class.administrationpage.php');
	require_once(EXTENSIONS . '/documenter/lib/class.form.php');
	
	class contentExtensionDocumenterEdit extends AdministrationPage {
	
		function view() {	
			DocumentationForm::render();
		}
		
		function action() {
			$doc_id = $this->_context[0];
			if (@array_key_exists('delete', $_POST['action'])) {
				$page = $this->_Parent->Database->fetchRow(0, "SELECT * FROM tbl_documentation WHERE `id` = '$doc_id'");
				$this->_Parent->Database->delete('tbl_documentation', " `id` = '$doc_id'");
				redirect(URL . '/symphony/extension/documenter/');
			}
			
			if(@array_key_exists('save', $_POST['action'])){
				$fields = $_POST['fields'];
				$fields['pages'] = implode(',',$fields['pages']);

				$this->_errors = array();

				if(!isset($fields['content']) || trim($fields['content']) == '') $this->_errors['content'] = __('Content is a required field');
			
				if(!isset($fields['pages']) || trim($fields['pages']) == '') $this->_errors['pages'] = __('Page is a required field');	

				if(empty($this->_errors)){
					if(!$this->_Parent->Database->update($fields, 'tbl_documentation', "`id` = '$doc_id'")) $this->pageAlert(__('Unknown errors occurred while attempting to save. Please check your <a href="%s">activity log</a>.', array(URL.'/symphony/system/log/')), Alert::ERROR);

					else{	
						redirect(URL . "/symphony/extension/documenter/edit/$doc_id/saved/");
					}
				}
			}
			
			if(is_array($this->_errors) && !empty($this->_errors)) $this->pageAlert(__('An error occurred while processing this form. <a href="#error">See below for details.</a>'), Alert::ERROR);				
		}
	
	}