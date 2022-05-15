<?php

namespace Salle\PixSalle\Repository;

interface PortfolioRepository
{
	public function getAllUserAlbums($userId);

	public function getAlbumPhotosFromUser($albumId, $userId);

	public function createAlbum($userId, $albumName);

	public function deleteAlbumOrPhoto($albumId, $userId);

	public function addPhotoToAlbum($albumId, $userId, $photoId);

	public function checkIfPortfolioExists($userId);

	public function createPortfolio($userId);

}