<script setup lang="ts">
import Dashboard from '@/Layouts/Dashboard.vue';
import { router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Trash, Eye, Plus } from 'lucide-vue-next';

import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { toast } from 'vue-sonner';
import BookForm from '@/components/BookForm.vue';
import { computed, ref } from 'vue';
import { Book, Author } from '@/types';

const page = usePage<{
    books: {
        data: Book[];
    };
    authors: Author[];
}>();

const books = computed(() => page.props.books);
const authors = page.props.authors;

const selectedBook = ref<Book | null>(null);

const formatDate = (dateString) => {
    if (!dateString) return 'Não publicado';
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
};

const handleDeleteBook = (id: number) => {
    if (confirm('Tem certeza que deseja excluir este livro?')) {
        router.delete(`/book/${id}`, {
            onSuccess: () => {
                toast.success('Livro excluído com sucesso!');
                router.reload({only: ['books']})
            },
            onError: () => {
                toast.error('Erro ao excluir o livro.');
            },
        });
    }
};

const isModalVisible = ref(false);
const handleViewBook = (book?: Book) => {
    selectedBook.value = book;
    isModalVisible.value = true;
};


</script>

<template>
    <Dashboard title="Livros">
        <BookForm :book="selectedBook" :authors="authors" v-model:isVisible="isModalVisible" @update:isVisible="isModalVisible = $event" />
        <div class="w-full flex justify-end align-middle">
            <Button 
                @click="handleViewBook()"
                class="flex justify-between align-middle gap-2 cursor-pointer">
                <Plus />
                <h1>Adicionar Livro</h1>
            </Button>
        </div>
        
        <Table>
            <TableCaption>Lista de livros.</TableCaption>
            <TableHeader>
                <TableRow>
                    <TableHead class="w-[100px]"> # </TableHead>
                    <TableHead>Nome</TableHead>
                    <TableHead class="text-center">Autor</TableHead>
                    <TableHead class="text-center"> Publicação </TableHead>
                    <TableHead class="text-right"> Ações </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="book in books.data" :key="book.id">
                    <TableCell class="font-medium">
                        {{ book.id }}
                    </TableCell>
                    <TableCell>{{ book.name }}</TableCell>
                    <TableCell class="text-center">{{ book.author.name }}</TableCell>
                    <TableCell class="text-center"> {{ formatDate(book.published_at) }} </TableCell>
                    <TableCell class="text-right flex items-center justify-end gap-2">
                        <Button
                            @click.prevent="handleViewBook(book)" 
                            class="p-2 text-blue-500 bg-blue-50 hover:bg-blue850 rounded-md transition-colors duration-200">
                            <Eye class="w-4 h-4" />
                        </Button>
                        <Button @click.prevent="handleDeleteBook(book.id)" class="p-2 text-red-500 bg-red-50 hover:bg-red-80 rounded-md transition-colors duration-200">
                            <Trash class="w-4 h-4" />
                        </Button>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </Dashboard>
</template>
