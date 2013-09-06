<?php

namespace App\Models;

use Scabbia\Extensions\Models\Model;

/**
 * @ignore
 */
class constantModel extends Model
{
    /**
     * @ignore
     */
    public $types = array(
        'issue_type' => 'Issue Type',
        'project_type' => 'Project Type',
        'open_issue_type' => 'Open Issue Type',
        'closed_issue_type' => 'Closed Issue Type'
    );


    /**
     * @ignore
     */
    public function getConstants()
    {
        return $this->db->createQuery()
            ->setTable('constants')
            ->addField('*')
            ->get()
            ->allWithKey('id');
    }

    /**
     * @ignore
     */
    public function getConstantsCount()
    {
        return $this->db->createQuery()
            ->setTable('constants')
            ->addField('COUNT(0)')
            ->get()
            ->scalar();
    }

    /**
     * @ignore
     */
    public function getConstantsWithPaging($uOffset = 0, $uLimit = 20)
    {
        $tResult = $this->db->createQuery()
            ->setTable('constants')
            ->setFields('*')
            ->setOffset($uOffset)
            ->setLimit($uLimit)
            ->get()
            ->all();

        return $tResult;
    }

    /**
     * @ignore
     */
    public function get($uId)
    {
        $tResult = $this->db->createQuery()
            ->setTable('constants')
            ->setFields('*')
            ->setWhere('id=:id')
            ->setLimit(1)
            ->addParameter('id', $uId)
            ->get()
            ->row();

        return $tResult;
    }

    /**
     * @ignore
     */
    public function insert($insert)
    {
        $tResult = $this->db->createQuery()
            ->setTable('constants')
            ->setFields($insert)
            ->insert()
            ->execute(true);

        return $tResult;
    }

    /**
     * @ignore
     */
    public function update($id, $update)
    {
        $tResult = $this->db->createQuery()
            ->setTable('constants')
            ->setFields($update)
            ->addParameter('id', $id)
            ->setWhere('id=:id')
            ->setLimit(1)
            ->update()
            ->execute();

        return $tResult;
    }

    /**
     * @ignore
     */
    public function delete($uId)
    {
        $tResult = $this->db->createQuery()
            ->setTable('constants')
            ->setWhere('id=:id')
            ->addParameter('id', $uId)
            ->setLimit(1)
            ->delete()
            ->execute();

        return $tResult;
    }
}