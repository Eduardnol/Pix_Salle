<?php

namespace Salle\PixSalle\Repository;

interface PortfolioRepository
{
	public function getAllUserAlbums($userId);

	public function getAlbumPhotosFromUser($albumId, $userId);

	public function createAlbum($userId, $albumName);

	public function deleteAlbum($albumId, $userId);

	public function deletePhotoFromAlbum($albumId, $userId, $photoId);

	public function addPhotoToAlbum($albumId, $userId, $imageURL);

	public function checkIfPortfolioExists($userId);

	public function createPortfolio($userId, $title);

}