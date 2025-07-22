<script setup>
import { cn } from "@/lib/utils";
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm, Link } from '@inertiajs/vue3';
import InputError from "./InputError.vue";

const props = defineProps({
    class: { type: null, required: false },
    errors: Object,
});

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post('/login', {
        onFinish: () => {
            form.reset('password');
        }
    })
}

</script>

<template>
    <div :class="cn('flex flex-col gap-6', props.class)">
        <Card>
            <CardHeader>
                <CardTitle>Bem Vindo</CardTitle>
            </CardHeader>
            <CardContent>
                <form>
                    <div class="flex flex-col gap-6">
                        <div class="grid">
                            <Label class="mb-3" for="email">Email</Label>
                            <Input
                                   id="email"
                                   type="email"
                                   v-model="form.email"
                                   placeholder="m@example.com"
                                   required />
                            <InputError v-if="form.errors.email" :text="form.errors.email" />
                        </div>
                        <div class="grid">
                            <Label class="mb-3" for="password">Password</Label>
                            <Input
                                   id="password"
                                   type="password"
                                   v-model="form.password"
                                   required />
                            <InputError v-if="form.errors.password" :text="form.errors.password" />
                        </div>
                        <div class="flex flex-col gap-3">
                            <Button type="submit" class="w-full" @click.prevent="submit"> Login </Button>
                        </div>
                    </div>
                    <div class="mt-4 text-center text-sm">
                        Não tem uma conta?
                        <Link href="/register" class="underline underline-offset-4"> Cadastre-se </Link>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
