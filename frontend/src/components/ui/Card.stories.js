import Card from './Card.vue'
import CardHeader from './CardHeader.vue'
import CardTitle from './CardTitle.vue'
import CardDescription from './CardDescription.vue'
import CardContent from './CardContent.vue'
import Button from './Button.vue'

export default {
  title: 'UI/Card',
  component: Card,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
}

export const Default = {
  render: () => ({
    components: { Card, CardHeader, CardTitle, CardDescription, CardContent },
    template: `
      <Card class="w-80">
        <CardHeader>
          <CardTitle>Card Title</CardTitle>
          <CardDescription>Card Description</CardDescription>
        </CardHeader>
        <CardContent>
          <p>This is the card content. You can put any content here.</p>
        </CardContent>
      </Card>
    `,
  }),
}

export const WithActions = {
  render: () => ({
    components: { Card, CardHeader, CardTitle, CardDescription, CardContent, Button },
    template: `
      <Card class="w-80">
        <CardHeader>
          <CardTitle>User Profile</CardTitle>
          <CardDescription>Manage your account settings</CardDescription>
        </CardHeader>
        <CardContent class="space-y-4">
          <div>
            <label class="text-sm font-medium">Name</label>
            <p class="text-sm text-gray-600">John Doe</p>
          </div>
          <div>
            <label class="text-sm font-medium">Email</label>
            <p class="text-sm text-gray-600">john@example.com</p>
          </div>
          <div class="flex gap-2">
            <Button size="sm">Edit</Button>
            <Button variant="outline" size="sm">Cancel</Button>
          </div>
        </CardContent>
      </Card>
    `,
  }),
}

export const StatCard = {
  render: () => ({
    components: { Card, CardHeader, CardTitle, CardContent },
    template: `
      <Card class="w-64">
        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
          <CardTitle class="text-sm font-medium">Total Students</CardTitle>
          <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
          </svg>
        </CardHeader>
        <CardContent>
          <div class="text-2xl font-bold">1,234</div>
          <p class="text-xs text-muted-foreground">+20.1% from last month</p>
        </CardContent>
      </Card>
    `,
  }),
}

export const MultipleCards = {
  render: () => ({
    components: { Card, CardHeader, CardTitle, CardDescription, CardContent },
    template: `
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <Card>
          <CardHeader>
            <CardTitle>Students</CardTitle>
            <CardDescription>Total enrolled students</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">1,234</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader>
            <CardTitle>Teachers</CardTitle>
            <CardDescription>Active teaching staff</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">45</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader>
            <CardTitle>Classes</CardTitle>
            <CardDescription>Total active classes</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">32</div>
          </CardContent>
        </Card>
      </div>
    `,
  }),
}
