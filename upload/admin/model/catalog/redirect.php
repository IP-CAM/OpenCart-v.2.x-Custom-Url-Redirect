<?php
class ModelCatalogRedirect extends Model {
	public function addRedirect($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "redirect SET redirect_from = '" . $this->db->escape($data['redirect_from']) . "', redirect_to = '" . $this->db->escape($data['redirect_to']) . "'");

		$redirect_id = $this->db->getLastId();

		$this->cache->delete('redirect');

		return $redirect_id;
	}

	public function editRedirect($redirect_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "redirect SET redirect_from = '" . $this->db->escape($data['redirect_from']) . "', redirect_to = '" . $this->db->escape($data['redirect_to']) . "' WHERE redirect_id = '" . (int)$redirect_id . "'");

		$this->cache->delete('redirect');
	}

	public function deleteRedirect($redirect_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "redirect WHERE redirect_id = '" . (int)$redirect_id . "'");

		$this->cache->delete('redirect');
	}

	public function getRedirect($redirect_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "redirect WHERE redirect_id = '" . (int)$redirect_id . "'");

		return $query->row;
	}

	public function getRedirects($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "redirect";

		$sort_data = array(
			'redirect_from',
			'redirect_to'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			//$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			//$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalRedirects() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "redirect");

		return $query->row['total'];
	}
}
