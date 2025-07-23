export interface Book {
    id: number;
    name: string;
    description: string;
    author: Author;
    published_at: string;
    image: string | null;
}

export interface BookFormProps {
    id: number;
    name: string;
    author_id: number;
    published_at: Date | string;
    file: File;
    [key: string]: any
}

export interface Author {
    id: number;
    name: string;
    active: boolean;
}