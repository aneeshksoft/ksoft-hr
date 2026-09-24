<?php

class Tracker_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->trackerDB = $this->load->database('tracker_db', TRUE);
    }

    public function get_employees($select, array $where = [], array $statements = [])
    {
        $this->trackerDB->select($select);
        $this->trackerDB->from("employee e");
        if (!empty($statements['join']) && is_string($statements['join'])) {
            $tables = explode(',', $statements['join']);
            if (in_array('ta', $tables)) {
                $this->trackerDB->join("task_allocation ta", 'ta.assigned_to=e.id', 'left');
            }
            if (in_array('t', $tables)) {
                $this->trackerDB->join("task t", 't.id=ta.task_id', 'left');
            }
            if (in_array('ts', $tables)) {
                $this->trackerDB->join("task_status ts", 'ts.task_id=t.id', 'left');
            }
        }

        if (!empty($where)) {
            $this->trackerDB->where($where);
            // if (!empty($statements['or_where'])) {
            //     $this->trackerDB->or_where($statements['or_where']);
            // }
            if (!empty($statements['or_where'])) {
                if (!empty($statements['group_or_where'])) {
                    // Wrap conditions with group_start/group_end if specified
                    $this->trackerDB->group_start();
                    $this->trackerDB->or_where($statements['or_where']);
                    $this->trackerDB->group_end();
                } else {
                    // Apply or_where directly
                    $this->trackerDB->or_where($statements['or_where']);
                }
            }
        }
        if (!empty($statements['where_in']) && is_array($statements['where_in'])) {
            foreach ($statements['where_in'] as $key => $values) {
                $this->trackerDB->where_in($key, $values);
            }
        }
        if (!empty($statements['group_by'])) {
            $this->trackerDB->group_by($statements['group_by']);
        }
        if (!empty($statements['order_by'])) {
            $this->trackerDB->order_by($statements['order_by']);
        }
        if (!empty($statements['or_like'])) {
            $this->trackerDB->group_start();
            $this->trackerDB->or_like($statements['or_like']);
            $this->trackerDB->group_end();
        }
        if (!empty($statements['limit'])) {
            $this->trackerDB->limit($statements['limit'], $statements['start'] ?? 0);
        }

        $query = $this->trackerDB->get();

        // echo $this->trackerDB->last_query();
        // exit;
        return $query;
    }

    
}
