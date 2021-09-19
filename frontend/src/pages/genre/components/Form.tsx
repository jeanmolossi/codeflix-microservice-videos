import React, { BaseSyntheticEvent, ChangeEvent, useCallback, useEffect, useState } from 'react';
import { Box, Button, ButtonProps, Checkbox, makeStyles, MenuItem, TextField } from "@material-ui/core";
import { useForm } from "react-hook-form";
import { Genre, genreHttp } from "../../../util/http/genre-http";
import { Category, categoryHttp } from "../../../util/http/category-http";

type FormProps = {}

type FormFields = Omit<Genre, 'id'>;

const useStyles = makeStyles(theme => ({
    submit: {
        marginRight: theme.spacing(1)
    }
}))

export const Form = ({ ..._ }: FormProps) => {
    const classes = useStyles();

    const buttonProps: ButtonProps = {
        className: classes.submit,
        variant: 'contained',
        color: 'secondary'
    }

    const {
        register,
        handleSubmit,
        getValues,
        setValue,
        watch
    } = useForm<FormFields>({
        defaultValues: {
            is_active: true,
            categories_id: []
        }
    });

    const [ categories, setCategories ] = useState<Category[]>([]);

    const onSubmit = useCallback((formData: FormFields, _: BaseSyntheticEvent | undefined) => {
        genreHttp.create(formData)
            .then((response) =>
                console.log(response.data.data)
            )
    }, []);

    const onSubmitOnly = () => onSubmit(getValues(), undefined)

    const onChange = useCallback((e: ChangeEvent<HTMLInputElement>) => {
        setValue(
            'categories_id',
            e.target.value as any
        )
    }, [ setValue ]);

    useEffect(() => {
        register('categories_id')
    }, [ register ]);

    useEffect(() => {
        categoryHttp.list()
            .then(response => setCategories(response.data.data))
    }, []);

    return (
        <form onSubmit={ handleSubmit(onSubmit) }>
            <TextField
                name={ 'name' }
                label={ 'Nome' }
                fullWidth
                variant={ 'outlined' }
                inputProps={ {
                    ...register('name')
                } }
            />

            <TextField
                select
                name={ 'categories_id' }
                value={ watch('categories_id') }
                label={ 'Categorias' }
                margin={ 'normal' }
                variant={ 'outlined' }
                placeholder={ 'Selecione categorias' }
                fullWidth
                SelectProps={ { multiple: true } }
                onChange={ onChange }
            >
                <MenuItem value="" disabled>
                    <em>Selecione categorias</em>
                </MenuItem>
                { categoriesOptionsHandler(categories) }
            </TextField>

            <Checkbox
                name={ 'is_active' }
                aria-label={ 'Ativo ?' }
                id={ 'is_active' }
                inputProps={ {
                    ...register('is_active')
                } }
                checked={ watch('is_active') }
                color={ 'primary' }
            />
            <label htmlFor={ 'is_active' }>Ativo ?</label>

            <Box dir={ 'rtl' }>
                <Button { ...buttonProps } onClick={ onSubmitOnly }>Salvar</Button>
                <Button { ...buttonProps } type={ 'submit' }>Salvar e continuar editando</Button>
            </Box>
        </form>
    )
}

function categoriesOptionsHandler(categories: Category[]) {
    return categories.map(category => (
        <MenuItem key={ category.id } value={ category.id }>{ category.name }</MenuItem>
    ))
}
