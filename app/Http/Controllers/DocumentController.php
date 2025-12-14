<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PhpOffice\PhpWord\IOFactory;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $documents = Document::latest('name')
            ->paginate(10);

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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'comment' => 'required|string',
            'file' => 'required|file|mimes:pdf,docx|max:10240',
            'convert' => 'nullable|boolean',
        ]);

        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            if ($extension === 'docx' && $request->convert == 1) {
                $filePath = $this->convertToPDF($file, $filename);
                $extension = 'pdf';
            } else {
                $filePath = $file->storeAs('documents', $filename, 'public');
            }

        } else {
            return back()->withErrors(['file' => 'File upload failed'])->withInput();
        }

        $document = Document::create([
            'name' => $request['name'],
            'type' => $extension,
            'user_id' => Auth::id(),
        ]);

        DocumentVersion::create([
            'version_number' => 1,
            'change_date' => now(),
            'file_url' => $filePath,
            'comment' => $request['comment'] ?? null,
            'document_id' => $document->id,
        ]);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $documents = (object) ['id' => 1, 'name' => 'Document1', 'type' => 'PDF', 'count' => 1, 'change_date' => now(), 'filename' => 'failas1', 'comment' => 'file :)', 'file_url' => '#'];

        return view('documents.show', compact('documents'));
    }

    public function edit(Document $document): View
    {
        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'comment' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,docx|max:10240',
            'convert' => 'nullable|boolean',
        ]);

        $extension = $document->type;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            if ($extension === 'docx' && $request->convert == 1) {
                $filePath = $this->convertToPDF($file, $filename);
                $extension = 'pdf';
            } else {
                $filePath = $file->storeAs('documents', $filename, 'public');
            }

            DocumentVersion::create([
                'document_id' => $document->id,
                'version_number' => $document->versions()->max('version_number') + 1,
                'change_date' => now(),
                'file_url' => $filePath,
                'comment' => $request->input('comment') ?? ' ',
            ]);
        }

        $document->versions()->latest('version_number')->first()->update([
            'comment' => $request->input('comment'),
        ]);

        $document->update([
            'name' => $request->input('name'),
            'type' => $extension,
        ]);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document): RedirectResponse
    {
        foreach ($document->versions as $version) {

            if (Storage::disk('public')->exists($version->file_url)) {
                Storage::disk('public')->delete($version->file_url);
            }
        }

        $document->versions()->delete();
        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document deleted successfully!');
    }

    public function download(Document $document)
    {
        $latestVersion = $document->versions()->latest('version_number')->first();

        if (! $latestVersion) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($latestVersion->file_url, $document->name.'.'.$document->type);
    }

    public function convertToPDF($file, $filename)
    {
        $phpWord = IOFactory::load($file->getPathname());
        ob_start();
        $xmlWriter = IOFactory::createWriter($phpWord, 'HTML');
        $xmlWriter->save('php://output');
        $htmlContent = ob_get_clean();

        $pdf = Pdf::loadHTML($htmlContent);

        $pdfFilename = pathinfo($filename, PATHINFO_FILENAME).'.pdf';
        $filePath = 'documents/'.$pdfFilename;
        $pdf->save(storage_path('app/public/'.$filePath));

        return $filePath;
    }
}
