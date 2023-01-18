<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gdpr_projects
{
    private $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function export($customer_id, $contact_id)
    {
        if (!class_exists('projects_model')) {
            $this->ci->load->model('projects_model');
        }

        $valAllowed = get_option('gdpr_contact_data_portability_allowed');
        if (empty($valAllowed)) {
            $valAllowed = [];
        } else {
            $valAllowed = unserialize($valAllowed);
        }

        $this->ci->db->where('clientid', $customer_id);
        $projects = $this->ci->db->get(db_prefix() . 'projects')->result_array();

        $this->ci->db->where('show_on_client_portal', 1);
        $this->ci->db->where('fieldto', 'projects');
        $this->ci->db->order_by('field_order', 'asc');
        $custom_fields = $this->ci->db->get(db_prefix() . 'customfields')->result_array();
		$task_fields = "id,name,tasktype,description,priority,dateadded,datemodified,startdate,duedate,datefinished,addedfrom,is_added_from_contact,status,send_reminder,recurring_type,repeat_every,recurring,is_recurring_from,cycles,total_cycles,custom_recurring,last_recurring_date,rel_id,rel_type,is_public,contacts_id,billable,billed,invoice_id,hourly_rate,milestone,kanban_order,milestone_order,visible_to_client,deadline_notified,source_from,imported_id,call_request_id,call_code,call_msg";	
        foreach ($projects as $projectsKey => $project) {
            if (in_array('related_tasks', $valAllowed)) {
                $sql = 'SELECT '.$task_fields.' FROM ' . db_prefix() . 'tasks WHERE (rel_id="' . $project['id'] . '" AND rel_type="project"';
                $sql .= ' AND addedfrom=' . $contact_id . ' AND is_added_from_contact=1) OR (id IN (SELECT(taskid) FROM ' . db_prefix() . 'task_comments WHERE contact_id=' . $contact_id . '))';
                $tasks = $this->ci->db->query($sql)->result_array();

                foreach ($tasks as $taskKey => $task) {
                    $this->ci->db->where('taskid', $task['id']);
                    $this->ci->db->where('contact_id', $contact_id);
                    $tasks[$taskKey]['comments'] = $this->ci->db->get(db_prefix() . 'task_comments')->result_array();
                }
                $projects[$projectsKey]['tasks'] = $tasks;
            }

            if (in_array('related_discussions', $valAllowed)) {
                $sql = 'SELECT id,project_id,subject,description,show_to_customer,datecreated,last_activity,staff_id,contact_id FROM ' . db_prefix() . 'projectdiscussions WHERE (project_id="' . $project['id'] . '"';
                $sql .= ' AND contact_id=' . $contact_id . ') OR (id IN (SELECT(discussion_id) FROM ' . db_prefix() . 'projectdiscussioncomments WHERE contact_id=' . $contact_id . ' AND discussion_type="regular"))';

                $discussions = $this->ci->db->query($sql)->result_array();

                foreach ($discussions as $discussionKey => $discussion) {
                    $this->ci->db->where('discussion_id', $discussion['id']);
                    $this->ci->db->where('discussion_type', 'regular');
                    $this->ci->db->where('contact_id', $contact_id);
                    $discussions[$discussionKey]['comments'] = $this->ci->db->get(db_prefix() . 'projectdiscussioncomments')->result_array();
                }

                $projects[$projectsKey]['discussions'] = $discussions;
            }

            if (in_array('projects_activity_log', $valAllowed)) {
                $this->ci->db->where('project_id', $project['id']);
                $this->ci->db->where('contact_id', $contact_id);
                $projects[$projectsKey]['activity'] = $this->ci->db->get(db_prefix() . 'project_activity')->result_array();
            }

            $projects[$projectsKey]['additional_fields'] = [];
            foreach ($custom_fields as $cf) {
                $projects[$projectsKey]['additional_fields'][] = [
                    'name'  => $cf['name'],
                    'value' => get_custom_field_value($project['id'], $cf['id'], 'projects'),
                ];
            }
        }

        return $projects;
    }
}
