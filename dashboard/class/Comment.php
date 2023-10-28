<?php

class Comment {
    private $commentsTable = 'cms_comments';
    private $db;
    public $movieId;
    public $userId;
    public $commentText;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addComment() {
        $query = "INSERT INTO $this->commentsTable (mid, uid, comment_text) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iis", $this->movieId, $this->userId, $this->commentText);

        if ($stmt->execute()) {
            return true; // Comment added successfully
        } else {
            return false; // Error occurred
        }
    }

    public function getCommentsByMovieId() {
        $query = "SELECT * FROM $this->commentsTable WHERE mid = ? ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $this->movieId);
        $stmt->execute();

        $result = $stmt->get_result();
        $comments = [];

        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }

        return $comments;
    }

    public function getCommentsByUserId() {
        $query = "SELECT * FROM $this->commentsTable WHERE uid = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $this->userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $comments = [];

        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }

        return $comments;
    }
}

// Example usage:
// $db is your database connection
// $comment = new Comment($db);
// $comment->movieId = 1;
// $comment->userId = 123;
// $comment->commentText = "Great movie!";
// $comment->addComment();
// $movieComments = $comment->getCommentsByMovieId();
// $userComments = $comment->getCommentsByUserId();

?>
