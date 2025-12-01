<?php
require_once __DIR__ . "/../../koneksi.php";
require_once __DIR__ . "/../../models/nontifikasi.php";


class NotificationController {

    private $model;

    public function __construct($db)
    {
        $this->model = new NotificationModel($db);
    }

    // Tambah Notif
    public function send($id_user, $role, $isi)
    {
        return $this->model->createNotif($id_user, $role, $isi);
    }

    // Ambil notifikasi untuk tampilan
    public function getNotifications($id_user, $role)
    {
        return $this->model->getAll($id_user, $role);
    }

    // Ambil unread → untuk badge
    public function getUnreadCount($id_user, $role)
    {
        $notif = $this->model->getUnread($id_user, $role);
        return count($notif);
    }

    // Mark all read
    public function markAllRead($id_user, $role)
    {
        return $this->model->markAllRead($id_user, $role);
    }

    
}

