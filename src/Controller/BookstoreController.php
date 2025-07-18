<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookstoreController extends AbstractController
{
    #[Route('/bookstorepreview', name: 'bookstore_index')]
    public function index(): Response
    {
        // Sample book data - in a real application, this would come from a database
        $featuredBooks = [
            [
                'id' => 1,
                'title' => 'Simple way of peaceful life',
                'author' => 'Armor Ramsey',
                'price' => 40.00,
                'image' => 'https://images.pexels.com/photos/1370298/pexels-photo-1370298.jpeg?auto=compress&cs=tinysrgb&w=300&h=350&fit=crop',
                'category' => 'lifestyle'
            ],
            [
                'id' => 2,
                'title' => 'Great travel adventures',
                'author' => 'Sanchit Howdy',
                'price' => 38.00,
                'image' => 'https://images.pexels.com/photos/159711/books-bookstore-book-reading-159711.jpeg?auto=compress&cs=tinysrgb&w=300&h=350&fit=crop',
                'category' => 'travel'
            ],
            [
                'id' => 3,
                'title' => 'The lady beauty Scarlett',
                'author' => 'Arthur Doyle',
                'price' => 45.00,
                'image' => 'https://images.pexels.com/photos/256541/pexels-photo-256541.jpeg?auto=compress&cs=tinysrgb&w=300&h=350&fit=crop',
                'category' => 'romance'
            ],
            [
                'id' => 4,
                'title' => 'Once upon a time',
                'author' => 'Klien Marry',
                'price' => 35.00,
                'image' => 'https://images.pexels.com/photos/46274/pexels-photo-46274.jpeg?auto=compress&cs=tinysrgb&w=300&h=350&fit=crop',
                'category' => 'fiction'
            ]
        ];

        $popularBooks = [
            [
                'id' => 5,
                'title' => 'Portrait photography',
                'author' => 'Adam Silber',
                'price' => 40.00,
                'image' => 'https://images.pexels.com/photos/1370298/pexels-photo-1370298.jpeg?auto=compress&cs=tinysrgb&w=300&h=350&fit=crop',
                'category' => 'photography'
            ],
            [
                'id' => 6,
                'title' => 'Peaceful Enlightenment',
                'author' => 'Marmik Lama',
                'price' => 40.00,
                'image' => 'https://images.pexels.com/photos/694740/pexels-photo-694740.jpeg?auto=compress&cs=tinysrgb&w=300&h=350&fit=crop',
                'category' => 'spiritual'
            ],
            [
                'id' => 7,
                'title' => 'Life among the pirates',
                'author' => 'Armor Ramsey',
                'price' => 40.00,
                'image' => 'https://images.pexels.com/photos/159711/books-bookstore-book-reading-159711.jpeg?auto=compress&cs=tinysrgb&w=300&h=350&fit=crop',
                'category' => 'adventure'
            ]
        ];

        $specialOffers = [
            [
                'id' => 8,
                'title' => 'Simple way of peaceful life',
                'author' => 'Armor Ramsey',
                'price' => 40.00,
                'originalPrice' => 50.00,
                'image' => 'https://images.pexels.com/photos/1370298/pexels-photo-1370298.jpeg?auto=compress&cs=tinysrgb&w=300&h=350&fit=crop',
                'discount' => 20
            ],
            [
                'id' => 9,
                'title' => 'Great travel adventures',
                'author' => 'Sanchit Howdy',
                'price' => 38.00,
                'originalPrice' => 48.00,
                'image' => 'https://images.pexels.com/photos/159711/books-bookstore-book-reading-159711.jpeg?auto=compress&cs=tinysrgb&w=300&h=350&fit=crop',
                'discount' => 21
            ]
        ];

        $blogPosts = [
            [
                'id' => 1,
                'title' => 'Reading books always makes the moments happy',
                'date' => '2024-03-30',
                'category' => 'inspiration',
                'image' => 'https://images.pexels.com/photos/1370298/pexels-photo-1370298.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop'
            ],
            [
                'id' => 2,
                'title' => 'The power of storytelling in modern literature',
                'date' => '2024-03-29',
                'category' => 'literature',
                'image' => 'https://images.pexels.com/photos/159711/books-bookstore-book-reading-159711.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop'
            ],
            [
                'id' => 3,
                'title' => 'Building a personal library: Tips and tricks',
                'date' => '2024-02-27',
                'category' => 'tips',
                'image' => 'https://images.pexels.com/photos/256541/pexels-photo-256541.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop'
            ]
        ];

        return $this->render('shop/bookstore/index.html.twig', [
            'featuredBooks' => $featuredBooks,
            'popularBooks' => $popularBooks,
            'specialOffers' => $specialOffers,
            'blogPosts' => $blogPosts,
            'bestSellingBook' => [
                'title' => 'Birds gonna be happy',
                'author' => 'Timbur Hood',
                'price' => 45.00,
                'description' => 'Discover the fascinating world of nature through this beautifully illustrated book that takes you on a journey through the wild. A perfect blend of storytelling and visual artistry.',
                'image' => 'https://images.pexels.com/photos/694740/pexels-photo-694740.jpeg?auto=compress&cs=tinysrgb&w=500&h=500&fit=crop'
            ]
        ]);
    }

    #[Route('/book/{id}', name: 'bookstore_book_detail')]
    public function bookDetail(int $id): Response
    {
        // This would fetch book details from database
        // For now, returning a simple response
        return $this->json([
            'id' => $id,
            'message' => 'Book details for ID: ' . $id
        ]);
    }

    #[Route('/search', name: 'bookstore_search')]
    public function search(): Response
    {
        // Search functionality would go here
        return $this->json([
            'message' => 'Search functionality - to be implemented'
        ]);
    }

    #[Route('/cart', name: 'bookstore_cart')]
    public function cart(): Response
    {
        // Cart functionality would go here
        return $this->json([
            'message' => 'Cart functionality - to be implemented'
        ]);
    }

    #[Route('/newsletter/subscribe', name: 'bookstore_newsletter_subscribe', methods: ['POST'])]
    public function subscribeNewsletter(): Response
    {
        // Newsletter subscription logic would go here
        return $this->json([
            'message' => 'Successfully subscribed to newsletter!'
        ]);
    }
}