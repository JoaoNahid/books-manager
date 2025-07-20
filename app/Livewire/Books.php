<?php

namespace App\Livewire;

use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class Books extends Component {
    use WithPagination;

    protected $showModal = false;
    protected $editMode = false;
    
    //Form fields
    public $book_id;
    public $name;
    public $description;
    public $published_at;
    public $author_id;


    public function render() {
        return view('livewire.books')->with([
            'books' => Book::with('author')->paginate(10),
            'authors' => Author::where('active', true)->get(),
            'showModal' => $this->showModal,
            'editMode' => $this->editMode,
        ]);
    }

    public function create() {
        $this->clearFormFields();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function edit($bookId) {
        $book = Book::findOrFail($bookId);

        $this->book_id = $book->id;
        $this->name = $book->name;
        $this->description = $book->description;
        $this->published_at = $book->published_at;
        $this->author_id = $book->author_id;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save() {
        // Get validation rules and messages
        $bookRequest = new BookRequest();
        $rules = $bookRequest->rules();
        $messages = $bookRequest->messages();

        // validate data
        $validatedData = Validator::make($this->getFormFields(),$rules, $messages);

        if ($validatedData->fails()) {
            session()->flash('error', $validatedData->errors()->first());
            return;
        }

        Book::updateOrCreate(
            ['id' => $this->book_id],
            $validatedData->validated()
        );

        session()->flash('message', 'Operação realizada com sucesso.');
    }

    public function delete($bookId) {
        Book::findOrFail($bookId)->delete();
        session()->flash('message', 'Livro excluído com sucesso.');
    }

    public function closeModal(): void {
        $this->showModal = false;
        $this->editMode = false;
        $this->clearFormFields();
        
    }

    private function getFormFields(): array {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'published_at' => $this->published_at,
            'author_id' => $this->author_id,
        ];
    }

    private function clearFormFields(): void {
        $this->book_id = '';
        $this->name = '';
        $this->description = '';
        $this->published_at = '';
        $this->author_id = '';
    }
}
