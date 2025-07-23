<script setup lang="ts">

import { Button } from '@/components/ui/button'
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogDescription
} from '@/components/ui/dialog'
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
} from '@/components/ui/form'

import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'

import { Input } from '@/components/ui/input'
import { router, useForm } from '@inertiajs/vue3'
import { BookFormProps, Book, Author } from '@/types';
import { toast } from 'vue-sonner'
import InputError from './InputError.vue'
import { watchEffect } from 'vue'

const props = defineProps<{
    book: Book | null,
    authors: Author[],
    isVisible: boolean
}>()
const emit = defineEmits(['update:isVisible']);

const form = useForm<BookFormProps>({
    id: null,
    name: '',
    description: '',
    published_at: '',
    author_id: null,
    file: null,
})

const formatDate = (date) => {
    return (new Date(date)).toISOString().split('T')[0];
}

watchEffect(() => {
    if (props.book) {
        form.id = props.book.id
        form.name = props.book.name
        form.description = props.book.description
        form.published_at = formatDate(props.book.published_at)
        form.author_id = props.book.author.id
    }
})

const submit = () => {
    form.post('/book', {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Livro cadastrado!');
            emit('update:isVisible', false);
            form.reset();
            router.reload({ only: ['books'] });
        }
    });
}

const toggleModal = (val) => {
    if (!val) {
        form.reset()
    }
    emit('update:isVisible', val)
}

const getImageUrl = (path: string | null) => {
    return path ? `/storage/${path}` : null;
};

</script>

<template>
    <Form as="" enctype="multipart/form-data">
        <Dialog :open="props.isVisible" @update:open="(val) => toggleModal(val)">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>{{ props.book ? 'Editar' : 'Adicionar' }} Livro</DialogTitle>
                    <DialogDescription></DialogDescription>
                </DialogHeader>

                <form enctype="multipart/form-data">
                    <input type="hidden" v-model="form.id" />

                    <div class="mb-3">
                        <FormField name="name">
                            <FormItem>
                                <FormLabel>Título *</FormLabel>
                                <FormControl>
                                    <Input type="text" v-model="form.name" placeholder="Título do livro" />
                                </FormControl>
                                <InputError v-if="form.errors.name" :text="form.errors.name" />
                            </FormItem>
                        </FormField>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <FormField name="author_id">
                            <FormItem>
                                <FormLabel>Autor *</FormLabel>
                                <FormControl>
                                    <Select v-model="form.author_id">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Selecione um autor" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem
                                                            v-for="author in authors"
                                                            :key="author.id"
                                                            :value="author.id">
                                                    {{ author.name }}
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                    <InputError v-if="form.errors.author_id" :text="form.errors.author_id" />
                                </FormControl>
                            </FormItem>
                        </FormField>

                        <FormField name="published_at" class="md:col-span-2">
                            <FormItem>
                                <FormLabel>Data de Publicação *</FormLabel>
                                <FormControl>
                                    <Input type="date" v-model="form.published_at"/>
                                </FormControl>
                                <InputError v-if="form.errors.published_at" :text="form.errors.published_at" />
                            </FormItem>
                        </FormField>
                    </div>

                    <div class="mb-3">
                        <FormField name="image" class="mb-3">
                            <FormItem>
                                <FormLabel>Capa do Livro</FormLabel>
                                <FormControl>
                                    <Input type="file" @change="e => form.file = e.target.files[0]" />
                                </FormControl>
                                <InputError v-if="form.errors['file']" :text="form.errors['file']" />
                            </FormItem>
                            <img v-if="props.book?.image" :src="getImageUrl(props.book.image)" alt="">
                        </FormField>
                    </div>

                    <div class="mb-3">
                        <FormField name="description" class="mb-3">
                            <FormItem>
                                <FormLabel>Descrição *</FormLabel>
                                <FormControl>
                                    <textarea
                                              v-model="form.description"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md"></textarea>
                                </FormControl>
                                <InputError v-if="form.errors.description" :text="form.errors.description" />
                            </FormItem>
                        </FormField>
                    </div>
                </form>

                <DialogFooter>
                    <Button class="bg-zinc-500 dark:bg-zinc-700" @click="toggleModal(false)">
                        Cancelar
                    </Button>
                    <Button type="submit" @click="submit" form="dialogForm">
                        {{ book ? 'Salvar' : 'Adicionar' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </Form>
</template>