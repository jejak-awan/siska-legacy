import StatCard from './StatCard.vue'

export default {
  title: 'UI/StatCard',
  component: StatCard,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    title: {
      control: { type: 'text' },
    },
    value: {
      control: { type: 'text' },
    },
    icon: {
      control: { type: 'select' },
      options: [
        'users', 'academic-cap', 'user-group', 'check-circle', 'x-circle', 
        'clock', 'star', 'document-check', 'trophy', 'shield-check', 
        'heart', 'flag', 'calendar', 'calendar-days', 'exclamation-triangle',
        'document', 'currency-dollar', 'home', 'chat-bubble-left-right',
        'map-pin', 'building-office', 'sparkles', 'bell', 'envelope',
        'signal', 'server', 'chart-bar'
      ],
    },
    color: {
      control: { type: 'select' },
      options: ['blue', 'green', 'purple', 'red', 'yellow', 'indigo', 'pink', 'emerald'],
    },
    trend: {
      control: { type: 'number' },
    },
    subtitle: {
      control: { type: 'text' },
    },
    loading: {
      control: { type: 'boolean' },
    },
  },
}

export const Default = {
  args: {
    title: 'Total Students',
    value: 1234,
    icon: 'users',
    color: 'blue',
  },
}

export const WithTrend = {
  args: {
    title: 'Active Students',
    value: 1156,
    icon: 'check-circle',
    color: 'green',
    trend: 12.5,
    subtitle: 'vs last month',
  },
}

export const WithNegativeTrend = {
  args: {
    title: 'Absent Students',
    value: 78,
    icon: 'x-circle',
    color: 'red',
    trend: -5.2,
    subtitle: 'vs last month',
  },
}

export const Loading = {
  args: {
    title: 'Loading Data',
    value: 0,
    icon: 'clock',
    color: 'yellow',
    loading: true,
  },
}

export const Teachers = {
  args: {
    title: 'Teachers',
    value: 45,
    icon: 'academic-cap',
    color: 'purple',
    trend: 2.1,
    subtitle: 'Active staff',
  },
}

export const Classes = {
  args: {
    title: 'Classes',
    value: 32,
    icon: 'building-office',
    color: 'indigo',
    trend: 0,
    subtitle: 'Active classes',
  },
}

export const Attendance = {
  args: {
    title: 'Attendance Rate',
    value: '94.2%',
    icon: 'check-circle',
    color: 'emerald',
    trend: 1.8,
    subtitle: 'This month',
  },
}

export const Extracurricular = {
  args: {
    title: 'Extracurricular',
    value: 15,
    icon: 'trophy',
    color: 'yellow',
    trend: 3,
    subtitle: 'Active programs',
  },
}

export const Counseling = {
  args: {
    title: 'Counseling Sessions',
    value: 28,
    icon: 'chat-bubble-left-right',
    color: 'pink',
    trend: -2.5,
    subtitle: 'This month',
  },
}

export const AllStats = {
  render: () => ({
    components: { StatCard },
    template: `
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard
          title="Total Students"
          :value="1234"
          icon="users"
          color="blue"
          :trend="12.5"
          subtitle="vs last month"
        />
        <StatCard
          title="Teachers"
          :value="45"
          icon="academic-cap"
          color="purple"
          :trend="2.1"
          subtitle="Active staff"
        />
        <StatCard
          title="Classes"
          :value="32"
          icon="building-office"
          color="indigo"
          :trend="0"
          subtitle="Active classes"
        />
        <StatCard
          title="Attendance Rate"
          value="94.2%"
          icon="check-circle"
          color="emerald"
          :trend="1.8"
          subtitle="This month"
        />
      </div>
    `,
  }),
}

export const DashboardView = {
  render: () => ({
    components: { StatCard },
    template: `
      <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <StatCard
            title="Total Students"
            :value="1234"
            icon="users"
            color="blue"
            :trend="12.5"
            subtitle="vs last month"
          />
          <StatCard
            title="Active Students"
            :value="1156"
            icon="check-circle"
            color="green"
            :trend="8.2"
            subtitle="vs last month"
          />
          <StatCard
            title="Absent Students"
            :value="78"
            icon="x-circle"
            color="red"
            :trend="-5.2"
            subtitle="vs last month"
          />
          <StatCard
            title="Teachers"
            :value="45"
            icon="academic-cap"
            color="purple"
            :trend="2.1"
            subtitle="Active staff"
          />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <StatCard
            title="Classes"
            :value="32"
            icon="building-office"
            color="indigo"
            :trend="0"
            subtitle="Active classes"
          />
          <StatCard
            title="Extracurricular"
            :value="15"
            icon="trophy"
            color="yellow"
            :trend="3"
            subtitle="Active programs"
          />
          <StatCard
            title="Counseling Sessions"
            :value="28"
            icon="chat-bubble-left-right"
            color="pink"
            :trend="-2.5"
            subtitle="This month"
          />
        </div>
      </div>
    `,
  }),
}
