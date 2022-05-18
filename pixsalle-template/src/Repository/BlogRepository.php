<?php

declare(strict_types=1);

namespace Salle\PixSalle\Repository;

interface BlogRepository
{
	public function createBlog(string $title, string $comment);

	public function showBlogs();
}