CREATE DATABASE IF NOT EXISTS video_place;

CREATE TABLE IF NOT EXISTS video_place.user (
  id         INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(120) NOT NULL,
  email      VARCHAR(120) NOT NULL,
  created_at DATETIME     NOT NULL
);