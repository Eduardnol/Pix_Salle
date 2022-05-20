<?php

declare(strict_types=1);

namespace Salle\PixSalle\Model;

use JsonSerializable;

class Blog implements JsonSerializable
{
	private string $title;
	private string $content;
	private int $userId;


	public function __construct()
	{

	}

	public function jsonSerialize()
	{
		return
			[
				'title' => $this->getTitle(),
				'content' => $this->getContent(),
				'userId' => $this->getUserId()
			];
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getContent(): string
	{
		return $this->content;
	}

	/**
	 * @param string $content
	 */
	public function setContent(string $content): void
	{
		$this->content = $content;
	}

	/**
	 * @return int
	 */
	public function getUserId(): int
	{
		return $this->userId;
	}

	/**
	 * @param int $userId
	 */
	public function setUserId(int $userId): void
	{
		$this->userId = $userId;
	}
}