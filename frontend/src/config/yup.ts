/* eslint-disable no-template-curly-in-string */
import { setLocale } from 'yup';

const ptBR = {
    mixed: {
        required: '${path} e obrigatório'
    },
    string: {
        max: '${path} pode ter no maximo ${max} caracteres'
    },
    number: {
        min: '${path} deve ter no minima ${min} caracteres'
    },
    array: {
        min: 'Selecione no minimo ${min} ${path}'
    }
}

setLocale(ptBR);

export * from 'yup';
