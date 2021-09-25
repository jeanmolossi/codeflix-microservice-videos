import React, { BaseSyntheticEvent, useCallback, useEffect, useState } from 'react';
import { Checkbox, FormControlLabel, TextField } from "@material-ui/core";
import { useForm } from "react-hook-form";
import { categoryHttp } from "../../../util/http/category-http";
import { Category } from '../../../core/models'
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from '../../../config/yup';
import { useHistory, useParams } from "react-router-dom";
import { useSnackbar } from "notistack";
import { DefaultForm, SubmitActions } from "../../../components";

type FormFields = Omit<Category, 'id' | 'created_at' | 'updated_at' | 'deleted_at'>;


const validationSchema = yup.object().shape( {
    name: yup.string().label( 'Nome' ).required(),
    description: yup.string(),
    is_active: yup.boolean().label( 'Ativo' ).required()
} ) as yup.SchemaOf<FormFields>;

export const Form = () => {

    const { id } = useParams<{ id?: string }>();
    const history = useHistory();
    const snackbar = useSnackbar();

    const [ category, setCategory ] = useState<Category>( {} as Category );
    const [ loading, setLoading ] = useState( false );


    const {
        register,
        handleSubmit,
        getValues,
        watch,
        reset,
        formState: { errors },
        trigger
    } = useForm<FormFields>( {
        context: validationSchema,
        resolver: yupResolver( validationSchema ),
        defaultValues: {
            is_active: true,
        }
    } );

    const saveButtonsBehavior = useCallback( ( data: any, e?: BaseSyntheticEvent, id?: string ) => {
        if ( !!e ) {
            (!!id
                    ? history.replace( `/categorias/${ data.data.id }/editar` )
                    : history.push( `/categorias/${ data.data.id }/editar` )
            )
            return;
        }

        history.push( `/categorias` )
    }, [ history ] );

    const onSubmit = useCallback( ( formData: FormFields, e: BaseSyntheticEvent | undefined ) => {
        const httpRequest = category.id
            ? categoryHttp.update( category.id, formData )
            : categoryHttp.create( formData );

        setLoading( true )
        httpRequest
            .then( ( { data } ) => {
                snackbar.enqueueSnackbar(
                    'Categoria salva com sucesso!',
                    { variant: "success" }
                )

                saveButtonsBehavior( data, e, id )
            } )
            .catch( ( err ) => {
                snackbar.enqueueSnackbar(
                    err.message,
                    { variant: "error" }
                )
            } )
            .finally( () => setLoading( false ) )
    }, [ category, id, saveButtonsBehavior, snackbar ] );

    const onSubmitOnly = useCallback(
        () => {
            trigger().then( isValid => {
                if ( isValid ) {
                    onSubmit( getValues(), undefined )
                }
            } )
        },
        [ onSubmit, getValues, trigger ]
    )

    useEffect( () => {
        if ( !id ) {
            return;
        }

        categoryHttp.get( id )
            .then( ( { data } ) => {
                setCategory( data.data )
                reset( data.data )
            } )
    }, [ id, reset ] )

    return (
        <DefaultForm onSubmit={ handleSubmit( onSubmit ) }>
            <TextField
                name={ 'name' }
                label={ 'Nome' }
                fullWidth
                variant={ 'outlined' }
                inputProps={ {
                    ...register( 'name', {
                        required: 'O campo nome e obrigatório'
                    } )
                } }
                disabled={ loading }
                InputLabelProps={ { shrink: true } }
                helperText={ errors.name?.message }
                error={ !!errors.name }
            />

            <TextField
                name={ 'description' }
                label={ 'Descrição' }
                multiline
                rows={ 4 }
                fullWidth
                variant={ 'outlined' }
                margin={ 'normal' }
                inputProps={ {
                    ...register( 'description' )
                } }
                disabled={ loading }
                InputLabelProps={ { shrink: true } }
            />

            <FormControlLabel
                disabled={ loading }
                control={
                    <Checkbox
                        name={ 'is_active' }
                        aria-label={ 'Ativo ?' }
                        id={ 'is_active' }
                        inputProps={ {
                            ...register( 'is_active' )
                        } }
                        checked={ watch( 'is_active' ) }
                        color={ 'primary' }
                    />
                }
                label={ 'Ativo ?' }
                labelPlacement={ 'end' }
            />

            <SubmitActions disabled={ loading } handleSubmit={ onSubmitOnly }/>
        </DefaultForm>
    )
}
