<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobPostingRequest;
use App\Http\Requests\UpdateJobPostingRequest;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        //$documents = Document::latest()
            //->paginate(10);
        $documents =[
        (object) ['id' => 1,'name' => 'Document1', 'type' => 'PDF'],
        (object) ['id' => 2,'name' => 'Document2', 'type' => 'DOCX']];

        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('documents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobPostingRequest $request): RedirectResponse
    {
        $document = Document::create($request->validated());

        return redirect()
            ->route('documents.show', $document)
            ->with('success', 'Document created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $documents = (object) ['id' => 1,'name' => 'Document1', 'type' => 'PDF', 'count' => 1, 'change_date' => now(), 'filename' => 'failas1', 'comment' => 'file :)', 'file_url' => '#'];
        return view('documents.show', compact('documents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $document =(object) ['id' => 1,'name' => 'Document1', 'type' => 'PDF', 'count' => 1, 'change_date' => now(), 'filename' => 'failas1', 'comment' => 'file :)', 'file_url' => '#'];
        return view('documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobPostingRequest $request, Document $document): RedirectResponse
    {
        // $jobPosting->update($request->validated());

        return redirect()
            ->route('documents.show', $document)
            ->with('success', 'Job posting updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        //$jobPosting->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document deleted successfully!');
    }
}
