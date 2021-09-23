import React, { BaseSyntheticEvent, ChangeEvent, useCallback, useEffect, useState } from 'react';
import {
    Box,
    Button,
    ButtonProps,
    Checkbox,
    FormControlLabel,
    makeStyles,
    MenuItem,
    TextField
} from "@material-ui/core";
import { useForm } from "react-hook-form";
import { Genre, genreHttp } from "../../../util/http/genre-http";
import { Category, categoryHttp } from "../../../util/http/category-http";
import { useHistory, useParams } from "react-router-dom";
import { useSnackbar } from "notistack";

type FormFields = Omit<Genre, 'id'>;

const useStyles = makeStyles(theme => ({
    submit: {
        marginRight: theme.spacing(1)
    }
}))

export const Form = () => {
    const classes = useStyles();
    const { id } = useParams<{ id?: string }>();
    const history = useHistory();
    const snackbar = useSnackbar();

    const [ loading, setLoading ] = useState(false);
    const [ categories, setCategories ] = useState<Category[]>([]);
    const [ genre, setGenre ] = useState({} as Genre);

    const buttonProps: ButtonProps = {
        className: classes.submit,
        variant: 'contained',
        color: 'secondary',
        disabled: loading,
    }


    const {
        register,
        handleSubmit,
        getValues,
        setValue,
        watch,
        reset
    } = useForm<FormFields>({
        defaultValues: {
            is_active: true,
            categories_id: []
        }
    });

    const saveButtonsBehavior = useCallback((data: any, e?: BaseSyntheticEvent, id?: string) => {
        if (!!e) {
            (!!id
                    ? history.replace(`/generos/${ data.data.id }/editar`)
                    : history.push(`/generos/${ data.data.id }/editar`)
            )
            return;
        }

        history.push('/generos')
    }, [ history ]);

    const onSubmit = useCallback((formData: FormFields, e: BaseSyntheticEvent | undefined) => {
        const http = genre.id
            ? genreHttp.update(genre.id, formData)
            : genreHttp.create(formData);

        setLoading(true)
        http
            .then(({ data }) => {
                snackbar.enqueueSnackbar(
                    'Genero salvo com sucesso',
                    { variant: "success" }
                )
                saveButtonsBehavior(data, e, id)
            })
            .catch((err) => {
                snackbar.enqueueSnackbar(
                    err.message,
                    { variant: "error" }
                )
            })
            .finally(() => setLoading(false))
    }, [ genre.id, saveButtonsBehavior, id, snackbar ]);

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

    useEffect(() => {
        if (id) {
            genreHttp.get(id)
                .then(({ data }) => {
                    setGenre(data.data)

                    const { categories } = data.data!;
                    delete data.data.categories;

                    const resetData = {
                        ...data.data,
                        categories_id: categories?.map(c => c.id)
                    }

                    reset(resetData)
                })
        }
    }, [ id, reset ])

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
                InputLabelProps={ {
                    shrink: true
                } }
                disabled={ loading }
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
                disabled={ loading }
                InputLabelProps={ {
                    shrink: true
                } }
            >
                <MenuItem value="" disabled>
                    <em>Selecione categorias</em>
                </MenuItem>
                { categoriesOptionsHandler(categories) }
            </TextField>

            <FormControlLabel
                control={
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
                }
                label={ 'Ativo ?' }
                labelPlacement={ 'end' }
            />

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
