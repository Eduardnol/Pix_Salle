<?php

declare(strict_types=1);

namespace Salle\PixSalle\Repository;

use Salle\PixSalle\Model\Blog;

interface BlogRepository
{
	public function createBlog(string $title, string $comment, int $userid);

	public function showBlogs();

	public function showSpecificBlog(int $blogId): bool|Blog;

	public function updateSpecificBlog(int $blogId, string $title, string $content, int $userId): Blog|array;

	public function deleteSpecificBlog(int $blogId, int $userId): bool|array;

}