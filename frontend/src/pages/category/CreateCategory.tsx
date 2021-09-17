import React from 'react';
import { Page } from "../../components";
import { Form } from "./components";

type CreateCategoryProps = {}

export const CreateCategory = ({}: CreateCategoryProps) => {
    return (
        <Page title={'Criar categoria'}>
            <Form />
        </Page>
    )
}
