<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'database.php'; // Ensure this line is already included

// Add video function
if (!function_exists('addVideo')) {
    function addVideo($title, $director, $release_year, $user_id) {
        global $conn;
        $sql = "INSERT INTO videos (title, director, release_year, user_id) VALUES (?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssi", $title, $director, $release_year, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Get videos function
if (!function_exists('getVideos')) {
    function getVideos($user_id) {
        global $conn;
        $sql = "SELECT id, title, director, release_year FROM videos WHERE user_id = ?";
        $videos = [];

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $videos[] = $row;
            }

            $stmt->close();
        }

        return $videos;
    }
}

// Get a single video by ID and user ID
if (!function_exists('getVideoById')) {
    function getVideoById($id, $user_id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM videos WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $video = $result->fetch_assoc();
        $stmt->close();
        return $video;
    }
}

// Update a video function
if (!function_exists('editVideo')) {
    function editVideo($id, $title, $director, $release_year, $user_id) {
        global $conn;
        $stmt = $conn->prepare("UPDATE videos SET title = ?, director = ?, release_year = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssiii", $title, $director, $release_year, $id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Delete a video function
if (!function_exists('deleteVideo')) {
    function deleteVideo($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM videos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}
?>
