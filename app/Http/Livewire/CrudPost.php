<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;

class CrudPost extends Component
{
    public $isOpen=false;
    public $post_id,$name,$slug,$extract,$body,$status,$category_id;
    public $categories;
    public function render()
    {
        $posts=Post::paginate(10);
        return view('livewire.crud-post',compact('posts'));

    }

    public function create(){
        $this->categories=Category::all();
        $this->openModal();
    }
    public function openModal(){
        $this->isOpen=true;
    }
    public function closeModal(){
        $this->isOpen=false;
    }
    private function resetInputsFields(){
        $this->name="";
        $this->slug="";
        $this->extract="";
        $this->body="";
        $this->status="";
    }
    public function store(){
        $this->validate([
            'name'=>'required',
            'slug'=>'required',
            'extract'=>'required',
            'body'=>'required',
            'status'=>'required'
        ]);
        Post::updateOrCreate(['id'=>$this->post_id],
            [
                'name'=>$this->name,
                'slug'=>$this->slug,
                'extract'=>$this->extract,
                'body'=>$this->body,
                'status'=>$this->status,
                'user_id'=> auth()->user()->id,
                'category_id'=> $this->category_id
            ]
        );
        session()->flash('message',
            $this->post_id?'Registro actualizado satisfactoriamente':'Registro creado satisfactoriamente.');
        $this->closeModal();
        $this->resetInputsFields();
    }

    public function edit(Post $post){
        $this->post_id=$post->id;
        $this->name=$post->name;
        $this->slug=$post-> slug;
        $this->extract=$post-> slug;
        $this->body=$post-> slug;
        $this->status=$post-> slug;
        $this->openModal();
    }

    public function delete(Post $post){
        $post->delete();
        session()->flash('message', 'Registro borrado satisfactoriamente.');
    }
}
