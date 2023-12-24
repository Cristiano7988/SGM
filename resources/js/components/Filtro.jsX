import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom';

const Filtro = ({ props }) => {
    const [filtro, setFiltro] = useState([]);
    const [focus, setFocus] = useState(false);
    const [request, setRequest] = useState(JSON.parse(props.request));
    const [alunos, setAlunos] = useState(JSON.parse(props.alunos));
    const [inputs, setInputs] = useState({
        matricular: {
            type: 'checkbox',
            value: 0,
            class: 'form-check-input m-0'
        },
        aluno: {
            query: 'aluno_id',
            type: 'select',
            value: alunos,
            class: 'form-select',
        },
        meses: {
            type: 'number',
            value: 0,
            class: 'form-control',
            props: {
                min: 0
            }
        },
        anos: {
            type: 'number',
            value: 0,
            class: 'form-control',
            props: {
                min: 0
            }
        }
    });

    const updateRequest = (e) => {
        let { name, checked, value } = e.target;
        
        // Aqui tratamos os valores dos inputs ou de inputs relacionados
        if (inputs[name].type == 'checkbox') value = !checked ? 0 : 1
        else if (name == 'meses') request['anos'] = (value / 12).toFixed(2).replace('.00', '');
        else if (name == 'anos') request['meses'] = (value * 12).toFixed(2).replace('.00', '');
        else if (name == 'aluno' && !request['aluno']) {
            delete request['meses'];
            delete request['anos'];
        }

        // Aqui atribuímos o valor ao request
        request[inputs[name].query ?? name] = value;

        // // Aqui removemos filtros não utilizados
        Object.entries(request).map(([name, value]) => {
            if (!parseFloat(value)) delete request[name];
        });

        // Aqui salvamos o estado
        setFocus(name);
        setRequest({ ...request });
    }

    useEffect(() => {
        if (!request) return;

        setFiltro('?' + Object.entries(request).map(item => item.join('=')).join('&'));
    }, [request]);

    return (
        <div className="card">
            <div className="card-body">
                <div className='d-flex gap-5 justify-content-end'>
                    <div className='d-flex gap-3 justify-content-end'>
                        {Object.keys(inputs).map((name, key) => {
                            return <div key={key} className="d-flex align-items-center gap-2 justify-content-center">
                                {['checkbox', 'number'].includes(inputs[name].type) && (
                                    <>
                                        <label className="form-check-label text-capitalize">{name}</label>
                                        <input
                                            {...inputs[name].props}
                                            defaultChecked={request.matricular == 1 ? true : false}

                                            className={inputs[name].class}
                                            type={inputs[name].type}
                                            autoFocus={focus == name}
                                            value={request[name] ?? inputs[name].value}
                                            name={name}
                                            onChange={updateRequest}
                                        />
                                    </>
                                )}
                                {['select'].includes(inputs[name].type) && (
                                    <>
                                        <label className="form-check-label text-capitalize">{name}</label>
                                        <select
                                            className={inputs[name].class}
                                            autoFocus={focus == name}
                                            name={name}
                                            onChange={updateRequest}
                                            value={request[inputs[name].query ?? name] ?? ''}
                                        >
                                            <option value=''>Selecione</option>
                                            {inputs[name].value.map(option => <option
                                                key={option.id}
                                                value={option.id}
                                                children={option.nome}
                                            />)}
                                        </select>
                                    </>
                                )}
                            </div>
                        })}
                    </div>
                    <a href={location.pathname + filtro} className='btn btn-primary'>Filtrar</a>
                </div>
            </div>
        </div>
    );
}

export default Filtro;

const filtro = document.getElementById('filtro');
if (filtro) ReactDOM.render(<Filtro props={filtro.dataset} />, filtro);

