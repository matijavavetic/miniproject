<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\BlogPosts;
use App\Http\Requests\BlogPostRequest;
use Illuminate\Http\Response;

class BlogPostsController extends Controller
{

    /**
     * Retrieve all blog posts
     *
     * @param BlogPosts $blogPosts
     * @return Response
     */
    public function index(BlogPosts $blogPosts) : Response
    {
        $blogPostsCollection = $blogPosts->all();

        return $this->responseFactory->view('blog.index', compact('blogPostsCollection'));
    }

    /**
     * Returns view to create a new blog post
     *
     * @return Response
     */
    public function create() : Response
    {
        return $this->responseFactory->view('blog.create');
    }


    /**
     * Stores a newly created blog post into database
     *
     * @param BlogPostRequest $request
     * @return Response
     */
    public function store(BlogPostRequest $request) : Response
    {
        $data = $request->validationData();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('blogimages', $filename);
            $data['image'] = $filename;
        }

        $blogPost = new BlogPosts();

        $blogPost->create($data);

        return $this->responseFactory->view('home');
    }

    /**
     * Find blog post by id and edit it
     *
     * @param BlogPosts $blogPosts
     * @param int $id
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(BlogPosts $blogPosts, int $id) : Response
    {
        $blogPost = $blogPosts->findOrFail($id);

        $this->authorize('update', $blogPost);

        return $this->responseFactory->view('blog.edit', compact('blogPost'));
    }

    /**
     * Find blog post by id and update it
     *
     * @param BlogPostRequest $request
     * @param BlogPosts $blogPosts
     * @param int $id
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(BlogPostRequest $request, BlogPosts $blogPosts, int $id) : RedirectResponse
    {
        $blogPost = $blogPosts->findOrFail($id);

        $this->authorize('update', $blogPost);

        $data = $request->validationData();

        $blogPost->update($data);

        return $this->responseFactory->redirectToAction('BlogPostsController@edit', ['id' => $id]);
    }
}