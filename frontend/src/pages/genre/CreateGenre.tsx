import React from 'react';
import { Page } from "../../components";
import { Form } from "./components";
import { useParams } from "react-router-dom";

export const CreateGenre = () => {
    const { id } = useParams<{ id?: string }>();

    return (
        <Page title={ id ? 'Editar gênero' : 'Criar gênero' }>
            <Form />
        </Page>
    )
}
