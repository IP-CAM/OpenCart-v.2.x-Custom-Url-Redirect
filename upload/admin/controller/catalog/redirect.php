<?php
class ControllerCatalogRedirect extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/redirect');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/redirect');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/redirect');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/redirect');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_redirect->addRedirect($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/redirect', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/redirect');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/redirect');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_redirect->editRedirect($this->request->get['redirect_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/redirect', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/redirect');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/redirect');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $redirect_id) {
				$this->model_catalog_redirect->deleteRedirect($redirect_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/redirect', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/redirect', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/redirect/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/redirect/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['redirects'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$redirect_total = $this->model_catalog_redirect->getTotalRedirects();

		$results = $this->model_catalog_redirect->getRedirects($filter_data);

		foreach ($results as $result) {
			$data['redirects'][] = array(
				'redirect_id'     => $result['redirect_id'],
				'redirect_from'   => $result['redirect_from'],
				'redirect_to'     => $result['redirect_to'],
				'edit'            => $this->url->link('catalog/redirect/edit', 'token=' . $this->session->data['token'] . '&redirect_id=' . $result['redirect_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_redirect_from'] = $this->language->get('column_redirect_from');
		$data['column_redirect_to'] = $this->language->get('column_redirect_to');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_redirect_from'] = $this->url->link('catalog/redirect', 'token=' . $this->session->data['token'] . '&sort=redirect_from' . $url, true);
		$data['sort_redirect_to'] = $this->url->link('catalog/redirect', 'token=' . $this->session->data['token'] . '&sort=redirect_to' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $redirect_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/redirect', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($redirect_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($redirect_total - $this->config->get('config_limit_admin'))) ? $redirect_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $redirect_total, ceil($redirect_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/redirect_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['redirect_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');

		$data['entry_redirect_from'] = $this->language->get('entry_redirect_from');
		$data['entry_redirect_to'] = $this->language->get('entry_redirect_to');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['redirect_from'])) {
			$data['error_redirect_from'] = $this->error['redirect_from'];
		} else {
			$data['error_redirect_from'] = '';
		}
		if (isset($this->error['redirect_to'])) {
			$data['error_redirect_to'] = $this->error['redirect_to'];
		} else {
			$data['error_redirect_to'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/redirect', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['redirect_id'])) {
			$data['action'] = $this->url->link('catalog/redirect/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/redirect/edit', 'token=' . $this->session->data['token'] . '&redirect_id=' . $this->request->get['redirect_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/redirect', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['redirect_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$redirect_info = $this->model_catalog_redirect->getRedirect($this->request->get['redirect_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['redirect_from'])) {
			$data['redirect_from'] = $this->request->post['redirect_from'];
		} elseif (!empty($redirect_info)) {
			$data['redirect_from'] = $redirect_info['redirect_from'];
		} else {
			$data['redirect_from'] = '';
		}

		if (isset($this->request->post['redirect_to'])) {
			$data['redirect_to'] = $this->request->post['redirect_to'];
		} elseif (!empty($redirect_info)) {
			$data['redirect_to'] = $redirect_info['redirect_to'];
		} else {
			$data['redirect_to'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/redirect_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/redirect')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['redirect_from']) < 2) || (utf8_strlen($this->request->post['redirect_from']) > 255)) {
			$this->error['redirect_from'] = $this->language->get('error_redirect_from');
		}

		if ((utf8_strlen($this->request->post['redirect_to']) < 2) || (utf8_strlen($this->request->post['redirect_to']) > 255)) {
			$this->error['redirect_to'] = $this->language->get('error_redirect_to');
		}

//		if (utf8_strlen($this->request->post['redirect_from']) > 0) {
//			$this->load->model('catalog/redirect');
//
//			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['redirect_from']);
//
//			if ($url_alias_info && isset($this->request->get['redirect_id']) && $url_alias_info['query'] != 'redirect_id=' . $this->request->get['redirect_id']) {
//				$this->error['redirect_from'] = sprintf($this->language->get('error_redirect_from'));
//			}
//
//			if ($url_alias_info && !isset($this->request->get['redirect_id'])) {
//				$this->error['redirect_from'] = sprintf($this->language->get('error_redirect_from'));
//			}
//		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/redirect')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}