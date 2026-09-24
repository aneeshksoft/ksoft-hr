<?php
class Work_location_model extends CI_Model
{
    /* Start Aneesh */

    public function get_work_locations($select, array $where = [], array $statements = [])
    {

        $this->db->select($select);
        $this->db->from('work_locations wl');
        if (!empty($statements['join']) && is_string($statements['join'])) {
            $tables = explode(',', $statements['join']);
            // if (in_array('pre_state', $tables)) {
            //     $this->db->join('state pre_state', 'pre_state.state_id=e.pre_state_id', 'left');
            // }
        }

        if (!empty($where)) {
            $this->db->where($where);
            if (!empty($statements['or_where'])) {
                $this->db->or_where($statements['or_where']);
            }
        }
        if (!empty($statements['group_by'])) {
            $this->db->group_by($statements['group_by']);
        }
        if (!empty($statements['order_by'])) {
            $this->db->order_by($statements['order_by']);
        }
        if (!empty($statements['or_like'])) {
            $this->db->group_start();
            $this->db->or_like($statements['or_like']);
            $this->db->group_end();
        }
        if (!empty($statements['limit'])) {
            $this->db->limit($statements['limit']);
        }

        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        return $query;
    }

    /* End Aneesh */
}
