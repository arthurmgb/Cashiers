<?php

namespace App\Http\Livewire;

use App\Models\Operator;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Configuracao extends Component
{

    use WithPagination;
    public $operador;
    protected $paginationTheme = 'bootstrap';
    public $qtd = 10;
    protected $listeners = ['render'];
    public $modal_start;

    public $rules = [

        'operador.nome' => 'required|max:100',

    ];

    protected $messages = [

        'operador.nome.required' => 'O nome do operador é obrigatório.',

    ];

    public function mount(){
        $this->modal_start = auth()->user()->modal_start;
    }

    public function confirmation()
    {
        $this->validate();
        $this->dispatchBrowserEvent('close-edit-modal');
        $this->dispatchBrowserEvent('show-edit-confirmation-modal');
    }

    public function resetOperation()
    {

        $this->dispatchBrowserEvent('close-edit-confirmation-modal');
    }

    public function alternate()
    {

        $this->dispatchBrowserEvent('close-edit-confirmation-modal');
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    public function edit(Operator $operador)
    {

        $this->operador = $operador;
    }

    public function update()
    {

        $this->operador->save();
        $this->dispatchBrowserEvent('close-edit-confirmation-modal');
        $this->emit('alert', 'Operador editado com sucesso!');
    }

    public function notifications($id){

        $find_user = User::find(auth()->user()->id);
        $find_user->modal_start = $id;
        $find_user->save();

        $this->modal_start = $find_user->modal_start;

    }

    public function toggleDefault($id){

        $default_operator = Operator::find($id);

        $defined_default = Operator::where('user_id', auth()->user()->id)
            ->where('is_default', 1)
            ->get();

        if($default_operator->is_default == 0){

            if($defined_default->isEmpty()){

                $default_operator->is_default = 1;
                $default_operator->save();

            }else{

                Operator::where('user_id', auth()->user()->id)
                ->where('is_default', 1)
                ->update(['is_default' => 0]);
                
                $default_operator->is_default = 1;
                $default_operator->save();
            }

            $this->emit('alert', 'Operador padrão alterado com sucesso!');

        }elseif($default_operator->is_default == 1){

            $default_operator->is_default = 0;
            $default_operator->save();

            $this->emit('alert', 'Operador padrão removido com sucesso!');

        }

    }
    
    public function render()
    {

        $operators = Operator::where('user_id', auth()->user()->id)
            ->latest('id')
            ->paginate($this->qtd);

        $operators_count = $operators->count();

        $default_operator_name = Operator::where('user_id', auth()->user()->id)
        ->where('is_default', 1)
        ->first();

        return view('livewire.configuracao', compact('operators', 'operators_count', 'default_operator_name'))
        ->layout('pages.configuracoes');
    }
}
